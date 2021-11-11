<?php

namespace App\Services\Admin;

use App\Exceptions;
use App\Exceptions\InvalidFormException;
use App\Http\Forms\Admin as Form;
use App\Http\Response as Response;
use App\Jobs\Admin as Jobs;
use App\Models\HistoryBaseModel;
use App\Models\Patient;
use App\Models\User;
use App\Repositories as Repos;
use App\Services\Support;
use App\Services\Support\Converter;
use App\Services\Support\CryptUtil;
use App\Services\Support\DateUtil;
use App\Services\Support\DBUtil;
use App\Services\Support\FileUtil;
use App\Services\Support\Logic\CsvLogic;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class VentilatorService
{
    use CsvLogic;

    public function getVentilatorData($path, $form = null)
    {
        $items_per_page = config('view.items_per_page');

        $offset = 0;

        $search_values = [];
        $http_query = '';

        if (!is_null($form)) {

            if (isset($form->page)) $offset = ($form->page - 1) * $items_per_page;

            $search_values = $this->buildVentilatorSearchValues($form);
            $http_query = '?' . http_build_query($search_values);
        }

        $ventilators = Repos\VentilatorRepository::findBySearchValuesAndOffsetAndLimit($offset, $items_per_page, $search_values);

        $total_count = Repos\VentilatorRepository::countBySearchValues($search_values);

        return Converter\VentilatorConverter::convertToAdminPaginate($ventilators, $total_count, $items_per_page, $path . $http_query);
    }

    public function getPatient(Form\VentilatorPatientForm $form)
    {
        $patient_code = Repos\VentilatorRepository::getPatientCodeById($form->id);

        return Converter\VentilatorConverter::convertToPatientResult($patient_code);
    }

    public function update(Form\VentilatorUpdateForm $form)
    {
        $ventilator = Repos\VentilatorRepository::findOneById($form->id);

        $ventilator->start_using_at = $form->start_using_at;

        DBUtil::Transaction(
            'MicroVent編集',
            function () use ($ventilator) {
                $ventilator->save();
            }
        );

        return new Response\SuccessJsonResult;
    }

    /**
     * ventilatorの削除は組織移動の際に行われる。
     * 選択されたventilatorに紐づくventilator_valueがすべて削除されている場合のみventilatorの削除が実行される。
     *
     * @param Form\VentilatorBulkDeleteForm $form
     * @return void
     */
    public function bulkDelete(Form\VentilatorBulkDeleteForm $form)
    {
        $ids = $form->ids;
        $deletable_row_limit = 50; //現在のデリート方式での仮の処理上限

        if (count($ids) > $deletable_row_limit) {
            $form->addError('ids', 'validation.excessive_number_of_deletions');
            throw new Exceptions\InvalidFormException($form);
        }

        $ventilator_value_exists = Repos\VentilatorValueRepository::existsByVentilatorIds($ids);

        if ($ventilator_value_exists) {
            $form->addError('ids', 'validation.ventilator_value_exists_yet');
            throw new Exceptions\InvalidFormException($form);
        }

        DBUtil::Transaction(
            'MicroVent削除',
            function () use ($ids) {
                Repos\VentilatorRepository::logicalDeleteByIds($ids);
            }
        );

        return new Response\SuccessJsonResult;
    }

    public function buildVentilatorSearchValues(Form\VentilatorSearchForm $form)
    {
        $search_values = [];

        if (isset($form->serial_number)) $search_values['serial_number'] = $form->serial_number;
        if (isset($form->organization_id)) $search_values['organization_id'] = $form->organization_id;
        if (isset($form->registered_user_name)) $search_values['registered_user_name'] = $form->registered_user_name;
        if (isset($form->expiration_date_from)) $search_values['expiration_date_from'] = $form->expiration_date_from;
        if (isset($form->expiration_date_to)) $search_values['expiration_date_to'] = $form->expiration_date_to;
        if (isset($form->start_using_at_from)) $search_values['start_using_at_from'] = $form->start_using_at_from;
        if (isset($form->start_using_at_to)) $search_values['start_using_at_to'] = $form->start_using_at_to;
        if (isset($form->has_bug)) $search_values['has_bug'] = $form->has_bug;

        return $search_values;
    }

    public function getBugList(Form\VentilatorBugsForm $form)
    {
        $bugs = Repos\VentilatorBugRepository::findByVentilatorId($form->id);

        return Converter\VentilatorConverter::convertToBugListData($bugs);
    }

    public function createVentilatorDataCsvByIds(string $filename, array $ids)
    {
        $query = Repos\VentilatorRepository::queryWithVentilatorValuesAndPatientsAndPatientValuesByids($ids);

        $header = config('ventilator_csv.header');

        $file_path = Support\FileUtil::jobTempFileUrl($filename);

        $this->createSearchDataCsv(
            $filename,
            array_values($header),
            function (Collection $entities) {
                return array_map(
                    function ($entity) {
                        return $this->buildVentilatorCsvRow($entity);
                    },
                    $entities->all()
                );
            },
            $query,
            500,
            $file_path
        );
    }

    public function buildVentilatorCsvRow($entity)
    {
        $row = [];

        $header = config('ventilator_csv.header');

        foreach (array_keys($header) as $key) {
            switch ($key) {
                case 'patient_exists':
                    $row[$key] = intval(!is_null($entity->patient_id));
                    break;
                case 'patient_value_exists':
                    $row[$key] = intval(!is_null($entity->patient_value_id));
                    break;
                case 'ventilator_value_exists':
                    $row[$key] = intval(!is_null($entity->ventilator_value_id));
                    break;
                default:
                    $row[$key] = strval($entity->$key);
            }
        }
        return $row;
    }

    /**
     * ジョブにキューを登録
     *
     * @param Form\VentilatorCsvExportForm $form
     */
    public function startQueueVentilatorDataCsvJob(Form\VentilatorCsvExportForm $form)
    {
        $ventilator_ids = $form->ids;

        //リクエストされたventilator_idがすべて存在している（削除されていない）ことを確認
        $count_request_ventilator_ids = count($ventilator_ids);
        $count_available_ventilator_ids = Repos\VentilatorRepository::countByIds($ventilator_ids);
        $all_ventilators_exist = ($count_request_ventilator_ids === $count_available_ventilator_ids);

        if (!$all_ventilators_exist) {
            $form->addError('ids', 'validation.id_not_found_contained');
            throw new Exceptions\InvalidFormException($form);
        }


        $now = Support\DateUtil::now();

        //キュー命名
        $queue = Support\DateUtil::toDatetimeChar($now) . '_ventilator_data';

        //ジョブにキューを登録。キュー処理開始
        Jobs\CreateVentilatorDataCsv::dispatchToHandle($queue, $ventilator_ids);

        return Converter\QueueConverter::convertToQueueStatusResult($queue);
    }

    /**
     * キューの状況を確認
     *
     * @param Form\QueueStatusCheckForm $form
     */
    public function checkStatusVentilatorDataCsvJob(Form\QueueStatusCheckForm $form)
    {
        $queue = $form->queue;

        $is_finished = Jobs\CreateVentilatorDataCsv::isQueueFinished($queue);

        $has_error = false;

        if ($is_finished) {
            $filename = Jobs\CreateVentilatorDataCsv::guessFilename($queue);
            //ファイルの存在確認
            $file_path = Support\FileUtil::jobTempFileUrl($filename);

            if (!Support\FileUtil::exists($file_path)) {
                // 作成失敗時
                $has_error = true;
            }
        }

        return Converter\QueueConverter::convertToQueueStatusResult($queue, $is_finished, $has_error);
    }

    /** 
     * CSVファイル情報を取得
     * 
     * @param  Form\Admin\QueueStatusCheckForm $form [description]
     * @return [type]                                [description]
     */
    public function getCreatedVentilatorDataCsvFilePath(Form\QueueStatusCheckForm $form)
    {
        $queue = $form->queue;

        //実際にCSV作成キューが完了しているかどうか
        $is_finished = Jobs\CreateVentilatorDataCsv::isQueueFinished($queue);

        if (!$is_finished) throw new Exceptions\HttpNotFoundException('');

        $filename = Jobs\CreateVentilatorDataCsv::guessFilename($queue);

        $file_path = Support\FileUtil::jobTempFileUrl($filename);

        //作成されたCSVが存在しているはずのパスに存在しているかどうか
        if (!Support\FileUtil::exists($file_path)) throw new Exceptions\HttpNotFoundException('');

        return $file_path;
    }

    /**
     * Csvジョブにキューを登録
     *
     * @param Form\VentilatorCsvImportForm $form
     */
    public function startQueueVentilatorDataImportJob(Form\VentilatorCsvImportForm $form, User $user)
    {
        $target_organization_id = $form->organization_id;
        $file = $form->csv_file;
        $file_path = FileUtil::getUploadedFilePath($file);
        $map_attribute_to_header = config('ventilator_csv.header');
        $map_attribute_to_validation_rule = config('ventilator_csv.validation_rule');
        $valid_rows_count = 0;
        $valid_rows_count_limit = 2000;
        $registered_user_id = $user->id;

        //組織の存在確認
        $exists_organization = Repos\OrganizationRepository::existsById($target_organization_id);
        if (!$exists_organization) {
            $form->addError('organization_id', 'validation.id_not_found');
            throw new Exceptions\InvalidFormException($form);
        }

        /**
         * バリデーションチェックのみを行い、エラーが見つかり次第返却とする。->processCsv利用
         *1. 規定のバリデーションチェック->都度返す。
         *2. インポート先でnull以外の患者番号重複がないかどうか->重複患者コードをまとめて返す。   
         */

        try {
            $this->processCsv(
                $file_path,
                $map_attribute_to_header,
                $map_attribute_to_validation_rule,
                null,
                function ($rows) use ($target_organization_id, &$valid_rows_count) { //インポート先の組織に登録済みの患者番号がないことを確認
                    $patient_codes = collect($rows)
                        ->pluck('patient_code')
                        ->unique()
                        ->filter()
                        ->all();

                    //重複する患者コードがあれば抽出。
                    $duplicated_patient_codes = Repos\PatientRepository::getPatientCodesByOrganizationIdAndPatientCodes($target_organization_id, $patient_codes);

                    if (!($duplicated_patient_codes->isEmpty())) {

                        $showable_patient_code_limit = 3;
                        //3件まで表示、4件以上ある場合は「...」付加(すでに同じCSVを読み込んでいる場合、全件表示されてしまうため)
                        if (count($duplicated_patient_codes) > $showable_patient_code_limit) {
                            $duplicated_patient_codes = $duplicated_patient_codes->slice(0, $showable_patient_code_limit)->concat(['...']);
                        }
                        $duplicated_patient_codes_str = implode(',', $duplicated_patient_codes->all());

                        throw new Exceptions\InvalidException('validation.csv_duplicated_patient_code', ['patient_code' => $duplicated_patient_codes_str]);
                    }

                    $valid_rows_count++;
                }
            );
        } catch (Exceptions\InvalidCsvException $e) {
            $error_message = $e->getMessage() . $e->finishedRowCountMessage;
            $form->addError('csv_file', $error_message);
            throw new Exceptions\InvalidFormException($form);
        }

        //行数制限
        if ($valid_rows_count > $valid_rows_count_limit) {
            $form->addError('csv_file', 'csv_too_many_rows', ['row_count_limit' => $valid_rows_count_limit]);
            throw new Exceptions\InvalidFormException($form);
        }

        //バリデーションエラーが見つからなければジョブ登録。
        //キュー命名
        $now = Support\DateUtil::now();
        $queue = Support\DateUtil::toDatetimeChar($now) . '_ventilator_data_import';

        //参照が失われるため、別名保存
        $filename = Jobs\CreateVentilatorDataCsv::guessFilename($queue);

        Support\FileUtil::putJobTemp($filename, $file);

        $file_path = Support\FileUtil::jobTempFileUrl($filename);
        //ジョブにキューを登録。キュー処理開始
        Jobs\ImportVentilatorData::dispatchToHandle($queue, $target_organization_id, $file_path, $registered_user_id);

        return Converter\QueueConverter::convertToQueueStatusResult($queue);
    }

    /**
     * キューの状況を確認
     *
     * @param Form\QueueStatusCheckForm $form
     */
    public function checkStatusVentilatorDataImportJob(Form\QueueStatusCheckForm $form)
    {
        $queue = $form->queue;

        $is_finished = Jobs\ImportVentilatorData::isQueueFinished($queue);

        if ($is_finished) {
            $filename = Jobs\CreateVentilatorDataCsv::guessFilename($queue);

            Support\FileUtil::deleteJobTemp($filename);
        }

        $has_error = false;

        return Converter\QueueConverter::convertToQueueStatusResult($queue, $is_finished, $has_error);
    }

    /**
     * バリデーション処理実施済みのインポート。
     * チャンク処理にて実行
     *
     * @param integer $organization_id
     * @param string $file_path
     * @param integer $registered_user_id
     * @return void
     */
    public function importVentilatorData(int $organization_id, string $file_path, int $registered_user_id)
    {
        $chunk_size = 500;
        // 新たに挿入されたventilator_idともともとのventilator_idのマッピング。
        $map_old_ventilator_id_to_new = [];
        //新たに挿入されたpatient_idともともとのpatient_idのマッピング
        $map_old_patient_id_to_new = [];
        //新たに挿入されたventilator_value列。chunkをまたいで同じventilator_idのventilator_valueが挿入されたときのhistoriesの重複対策。
        $saved_ventilator_value_ids = [];
        //新たに挿入されたpatient_value列。historiesの重複対策。
        $saved_patient_value_ids = [];

        $this->processValidatedCsv(
            $file_path,
            array_keys(config('ventilator_csv.header')),
            function ($rows) use ($organization_id, $registered_user_id, &$map_old_ventilator_id_to_new, &$map_old_patient_id_to_new, &$saved_ventilator_value_ids, &$saved_patient_value_ids) {

                \Log::debug('CHUNK START MEMORY=' . memory_get_usage(FALSE));

                //save対象のventilator列
                $map_old_ventilator_id_to_ventilator = $this->prepareVentilatorsForSave($rows, $organization_id, $registered_user_id, array_keys($map_old_ventilator_id_to_new));
                //save対象のpatient列
                $map_old_patient_id_to_patient = $this->preparePatientsForSave($rows, $organization_id, array_keys($map_old_patient_id_to_new));
                //bulk insert対象のpatient_value列
                $list_patient_value_for_bulk_insert = $this->preparePatientValuesForBulkInsert($rows, array_keys($map_old_patient_id_to_new));
                //bulk insert対象のventilator_value列
                $list_ventilator_value_for_bulk_insert = $this->prepareVentilatorValuesForBulkInsert($rows);

                /**
                 * トランザクション
                 * 1,patientsおよびventilatorsを個別にsaveし、新旧idのマッピングを作成
                 * 2,1のマッピングをもとにventialtor_valueおよびpatient_valueをバルクインサート
                 * 3,各種historyをバルクインサート
                 */
                DBUtil::Transaction(
                    'CSVインポート',
                    function () use (
                        $map_old_ventilator_id_to_ventilator,
                        $map_old_patient_id_to_patient,
                        $list_patient_value_for_bulk_insert,
                        $list_ventilator_value_for_bulk_insert,
                        $registered_user_id,
                        &$map_old_ventilator_id_to_new,
                        &$map_old_patient_id_to_new,
                        &$saved_ventilator_value_ids,
                        &$saved_patient_value_ids,
                    ) {
                        $update_target_ventilator_ids = [];
                        $update_target_ventilator_patient_ids = [];

                        if (!empty($map_old_ventilator_id_to_ventilator)) {
                            //insertするventilatorごとの新旧idの紐付けを行いたいので個別saveとする。
                            foreach ($map_old_ventilator_id_to_ventilator as $old_ventilator_id => $ventilator) {
                                $ventilator->save();

                                //patient insert後に対応ventialtor.patient_idをbulk update
                                if (!is_null($ventilator->patient_id)) {
                                    $update_target_ventilator_ids[] = $ventilator->id;
                                    $update_target_ventilator_patient_ids[] = $ventilator->patient_id;
                                }

                                $map_old_ventilator_id_to_new[$old_ventilator_id] = $ventilator->id;
                            }
                        }

                        if (!empty($map_old_patient_id_to_patient)) {
                            //ventilator同様、新旧idの紐付けのため個別にsave
                            foreach ($map_old_patient_id_to_patient as $old_patient_id => $patient) {

                                $patient->save();

                                $map_old_patient_id_to_new[$old_patient_id] = $patient->id;
                            }

                            //以下バルクupdate/insert処理
                            //ventilator bulk update
                            $update_target_ventilator_patient_ids = array_map(
                                function ($old_patient_id) use ($map_old_patient_id_to_new) {
                                    return $map_old_patient_id_to_new[$old_patient_id];
                                },
                                $update_target_ventilator_patient_ids
                            );

                            Repos\VentilatorRepository::updateBulkForPatientId(
                                $update_target_ventilator_ids,
                                $update_target_ventilator_patient_ids
                            );
                        }

                        //patient_valueバルクインサート
                        if (!empty($list_patient_value_for_bulk_insert['old_patient_id'])) {
                            $list_patient_value_for_bulk_insert['patient_id'] = array_map(
                                function ($old_patient_id) use ($map_old_patient_id_to_new) {
                                    return $map_old_patient_id_to_new[$old_patient_id];
                                },
                                $list_patient_value_for_bulk_insert['old_patient_id']
                            );


                            Repos\PatientValueRepository::insertBulk(
                                $list_patient_value_for_bulk_insert,
                                $registered_user_id
                            );

                            //patient_value_historiesバルクインサート
                            //現在のchunkで新たに挿入されたpatient_valueのpatient_id列でpatient_value_idをselect(これらpatient_idは必ずこのインポートで挿入されたものである)
                            //$saved_patient_value_idに含まれていないものを抽出→バルクインサート
                            //$saved_patient_value_idを更新
                            $chunk_saved_patient_value_ids = Repos\PatientValueRepository::getIdsByPatientIds($list_patient_value_for_bulk_insert['patient_id'])->all();
                            $chunk_saved_patient_value_ids = array_diff($chunk_saved_patient_value_ids, $saved_patient_value_ids);
                            Repos\PatientValueHistoryRepository::insertBulk(
                                $chunk_saved_patient_value_ids,
                                $registered_user_id,
                                HistoryBaseModel::CREATE
                            );

                            $saved_patient_value_ids = array_merge($saved_patient_value_ids, $chunk_saved_patient_value_ids);
                        }

                        //ventilator_valueバルクインサート
                        if (!empty($list_ventilator_value_for_bulk_insert['old_ventilator_id'])) {
                            $list_ventilator_value_for_bulk_insert['ventilator_id'] = array_map(
                                function ($old_ventilator_id) use ($map_old_ventilator_id_to_new) {
                                    return $map_old_ventilator_id_to_new[$old_ventilator_id];
                                },
                                $list_ventilator_value_for_bulk_insert['old_ventilator_id']
                            );

                            Repos\VentilatorValueRepository::insertBulk(
                                $list_ventilator_value_for_bulk_insert,
                                $registered_user_id
                            );

                            //ventilator_value_historiesバルクインサート
                            //現在のchunkで新たに挿入されたventilator_valueのventilator_id列でventilator_value_idをselect(これらventilator_idは必ずこのインポートで挿入されたものである)
                            //$saved_ventilator_value_idsに含まれていないものを抽出→バルクインサート
                            //$saved_ventialtor_value_idsを更新
                            $chunk_saved_ventilator_value_ids = Repos\VentilatorValueRepository::getIdsByVentilatorIds($list_ventilator_value_for_bulk_insert['ventilator_id'])->all();
                            $chunk_saved_ventilator_value_ids = array_diff($chunk_saved_ventilator_value_ids, $saved_ventilator_value_ids);
                            Repos\VentilatorValueHistoryRepository::insertBulk(
                                $chunk_saved_ventilator_value_ids,
                                $registered_user_id,
                                HistoryBaseModel::CREATE
                            );

                            $saved_ventilator_value_ids = array_merge($saved_ventilator_value_ids, $chunk_saved_ventilator_value_ids);
                        }
                    }
                );

                \Log::debug('CHUNK END MEMORY=' . memory_get_usage(FALSE));
            },
            $chunk_size
        );
    }

    private function prepareVentilatorsForSave(array $csv_rows, int $organization_id, int $registered_user_id, array $exceptive_ventilator_ids = [])
    {
        $map_old_ventilator_id_to_ventilator = [];
        foreach ($csv_rows as $row) {
            //呼吸器データ移行用準備
            //現在行のventilatorが登録またはsave対象となっているかどうか。除外対象（別のチャンク処理で登録済みの場合等）でも準備済みでもない場合
            $is_ventilator_for_save = !(in_array($row['ventilator_id'], $exceptive_ventilator_ids)
                || key_exists($row['ventilator_id'], $map_old_ventilator_id_to_ventilator));

            if ($is_ventilator_for_save) {
                $ventilator_exists = Repos\VentilatorRepository::existsByOrganizationIdAndId($organization_id, $row['ventilator_id']);

                if ($ventilator_exists) {
                    //インポート先の組織に登録済みの場合はスキップ
                    continue;
                }

                $gs1_code = strval($row['gs1_code']);
                $serial_number = strval($row['serial_number']);
                $city = isset($row['city']) ? strval($row['city']) : null;
                $qr_read_at = DateUtil::parseToDatetime($row['qr_read_at']);
                $expiration_date = !empty($row['expiration_date']) ? DateUtil::parseToDate($row['expiration_date']) : null;
                $start_using_at = DateUtil::parseToDatetime($row['start_using_at']);
                $patient_id = $row['patient_exists'] ? intval($row['patient_id']) : null;

                //save予約
                $map_old_ventilator_id_to_ventilator[$row['ventilator_id']] = Converter\VentilatorConverter::convertToVentilatorEntity($gs1_code, $serial_number, $expiration_date, $qr_read_at, null, null, $city, $organization_id, $registered_user_id, $start_using_at, $patient_id);
            }
        }

        return $map_old_ventilator_id_to_ventilator;
    }


    private function preparePatientsForSave(array $csv_rows, int $organization_id, array $exceptive_patient_ids)
    {
        $map_old_patient_id_to_patient = [];
        foreach ($csv_rows as $row) {
            $is_patient_for_save = $row['patient_exists']
                && !(in_array($row['patient_id'], $exceptive_patient_ids)
                    || key_exists($row['patient_id'], $map_old_patient_id_to_patient));

            if ($is_patient_for_save) {
                $patient_exists = Repos\PatientRepository::existsByOrganizationIdAndId($organization_id, $row['patient_id']);

                if ($patient_exists) {
                    //インポート先の組織に登録済みの場合はスキップ
                    continue;
                }

                $patient_code = ($row['patient_code'] !== '') ? strval($row['patient_code']) : null;
                $patient_height = strval($row['patient_height']);
                $patient_weight = strval($row['patient_weight']);
                $patient_gender = intval($row['patient_gender']);

                $map_old_patient_id_to_patient[$row['patient_id']] = Converter\PatientConverter::convertToEntity($patient_height, $patient_gender, $patient_weight, $patient_code, $organization_id);
            }
        }

        return $map_old_patient_id_to_patient;
    }

    private function preparePatientValuesForBulkInsert(array $csv_rows, array $exceptive_patient_ids)
    {
        $list_patient_value_for_bulk_insert = [];

        $list_patient_value_for_bulk_insert['old_patient_id'] = [];
        $list_patient_value_for_bulk_insert['registered_at'] = [];
        $list_patient_value_for_bulk_insert['opt_out_flg'] = [];
        $list_patient_value_for_bulk_insert['age'] = [];
        $list_patient_value_for_bulk_insert['vent_disease_name'] = [];
        $list_patient_value_for_bulk_insert['other_disease_name_1'] = [];
        $list_patient_value_for_bulk_insert['other_disease_name_2'] = [];
        $list_patient_value_for_bulk_insert['used_place'] = [];
        $list_patient_value_for_bulk_insert['hospital'] = [];
        $list_patient_value_for_bulk_insert['national'] = [];
        $list_patient_value_for_bulk_insert['discontinuation_at'] = [];
        $list_patient_value_for_bulk_insert['outcome'] = [];
        $list_patient_value_for_bulk_insert['treatment'] = [];
        $list_patient_value_for_bulk_insert['adverse_event_flg'] = [];
        $list_patient_value_for_bulk_insert['adverse_event_contents'] = [];

        foreach ($csv_rows as $row) {
            $is_patient_value_for_bulk_insert = $row['patient_value_exists']
                && !(in_array($row['patient_id'], $exceptive_patient_ids)
                    || in_array($row['patient_id'], $list_patient_value_for_bulk_insert['old_patient_id']));

            if ($is_patient_value_for_bulk_insert) {
                $list_patient_value_for_bulk_insert['old_patient_id'][] = $row['patient_id'];
                $list_patient_value_for_bulk_insert['registered_at'][] = !empty($row['patient_value_registered_at']) ? DateUtil::parseToDatetime($row['patient_value_registered_at']) : null;
                $list_patient_value_for_bulk_insert['age'][] = isset($row['age']) ? strval($row['age']) : null;
                $list_patient_value_for_bulk_insert['vent_disease_name'][] = isset($row['vent_disease_name']) ? strval($row['vent_disease_name']) : null;
                $list_patient_value_for_bulk_insert['other_disease_name_1'][] = isset($row['other_disease_name_1']) ? strval($row['other_disease_name_1']) : null;
                $list_patient_value_for_bulk_insert['other_disease_name_2'][] = isset($row['other_disease_name_2']) ? strval($row['other_disease_name_2']) : null;
                $list_patient_value_for_bulk_insert['used_place'][] = !empty($row['used_place']) ? intval($row['used_place']) : null;
                $list_patient_value_for_bulk_insert['hospital'][] = isset($row['hospital']) ? strval($row['hospital']) : null;
                $list_patient_value_for_bulk_insert['national'][] = isset($row['national']) ? strval($row['national']) : null;
                $list_patient_value_for_bulk_insert['discontinuation_at'][] = !empty($row['discontinuation_at']) ? DateUtil::parseToDatetime($row['discontinuation_at']) : null;
                $list_patient_value_for_bulk_insert['outcome'][] = !empty($row['outcome']) ? intval($row['outcome']) : null;
                $list_patient_value_for_bulk_insert['treatment'][] = !empty($row['treatment']) ? intval($row['treatment']) : null;
                $list_patient_value_for_bulk_insert['adverse_event_flg'][] = isset($row['adverse_event_flg']) ? intval($row['adverse_event_flg']) : null;
                $list_patient_value_for_bulk_insert['adverse_event_contents'][] = isset($row['adverse_event_contents']) ? strval($row['adverse_event_contents']) : null;
                $list_patient_value_for_bulk_insert['opt_out_flg'][] = isset($row['opt_out_flg']) ? intval($row['opt_out_flg']) : null;
            }
        }

        return $list_patient_value_for_bulk_insert;
    }

    private function prepareVentilatorValuesForBulkInsert(array $csv_rows)
    {
        $list_ventilator_value_for_bulk_insert = [];

        $list_ventilator_value_for_bulk_insert['old_ventilator_id'] = [];
        $list_ventilator_value_for_bulk_insert['appkey_id'] = [];
        $list_ventilator_value_for_bulk_insert['registered_at'] = [];
        $list_ventilator_value_for_bulk_insert['height'] = [];
        $list_ventilator_value_for_bulk_insert['weight'] = [];
        $list_ventilator_value_for_bulk_insert['gender'] = [];
        $list_ventilator_value_for_bulk_insert['ideal_weight'] = [];
        $list_ventilator_value_for_bulk_insert['airway_pressure'] = [];
        $list_ventilator_value_for_bulk_insert['total_flow'] = [];
        $list_ventilator_value_for_bulk_insert['air_flow'] = [];
        $list_ventilator_value_for_bulk_insert['o2_flow'] = [];
        $list_ventilator_value_for_bulk_insert['rr'] = [];
        $list_ventilator_value_for_bulk_insert['expiratory_time'] = [];
        $list_ventilator_value_for_bulk_insert['inspiratory_time'] = [];
        $list_ventilator_value_for_bulk_insert['vt_per_kg'] = [];
        $list_ventilator_value_for_bulk_insert['predicted_vt'] = [];
        $list_ventilator_value_for_bulk_insert['estimated_vt'] = [];
        $list_ventilator_value_for_bulk_insert['estimated_mv'] = [];
        $list_ventilator_value_for_bulk_insert['estimated_peep'] = [];
        $list_ventilator_value_for_bulk_insert['fio2'] = [];
        $list_ventilator_value_for_bulk_insert['status_use'] = [];
        $list_ventilator_value_for_bulk_insert['status_use_other'] = [];
        $list_ventilator_value_for_bulk_insert['spo2'] = [];
        $list_ventilator_value_for_bulk_insert['etco2'] = [];
        $list_ventilator_value_for_bulk_insert['pao2'] = [];
        $list_ventilator_value_for_bulk_insert['paco2'] = [];
        $list_ventilator_value_for_bulk_insert['fixed_flg'] = [];
        $list_ventilator_value_for_bulk_insert['fixed_at'] = [];
        $list_ventilator_value_for_bulk_insert['confirmed_flg'] = [];
        $list_ventilator_value_for_bulk_insert['confirmed_at'] = [];

        foreach ($csv_rows as $row) {
            if ($row['ventilator_value_exists']) {
                $list_ventilator_value_for_bulk_insert['old_ventilator_id'][] = $row['ventilator_id'];
                $list_ventilator_value_for_bulk_insert['appkey_id'][] = isset($row['appkey_id']) ? strval($row['appkey_id']) : null;
                $list_ventilator_value_for_bulk_insert['registered_at'][] = !empty($row['ventilator_value_registered_at']) ? DateUtil::parseToDatetime($row['ventilator_value_registered_at']) : null;
                $list_ventilator_value_for_bulk_insert['height'][] = isset($row['height']) ? strval($row['height']) : null;
                $list_ventilator_value_for_bulk_insert['weight'][] = isset($row['weight']) ? strval($row['weight']) : null;
                $list_ventilator_value_for_bulk_insert['gender'][] = isset($row['gender']) ? strval($row['gender']) : null;
                $list_ventilator_value_for_bulk_insert['ideal_weight'][] = isset($row['ideal_weight']) ? strval($row['ideal_weight']) : null;
                $list_ventilator_value_for_bulk_insert['airway_pressure'][] = isset($row['airway_pressure']) ? strval($row['airway_pressure']) : null;
                $list_ventilator_value_for_bulk_insert['total_flow'][] = isset($row['total_flow']) ? strval($row['total_flow']) : null;
                $list_ventilator_value_for_bulk_insert['air_flow'][] = isset($row['air_flow']) ? strval($row['air_flow']) : null;
                $list_ventilator_value_for_bulk_insert['o2_flow'][] = isset($row['o2_flow']) ? strval($row['o2_flow']) : null;
                $list_ventilator_value_for_bulk_insert['rr'][] = isset($row['rr']) ? strval($row['rr']) : null;
                $list_ventilator_value_for_bulk_insert['expiratory_time'][] = isset($row['expiratory_time']) ? strval($row['expiratory_time']) : null;
                $list_ventilator_value_for_bulk_insert['inspiratory_time'][] = isset($row['inspiratory_time']) ? strval($row['inspiratory_time']) : null;
                $list_ventilator_value_for_bulk_insert['vt_per_kg'][] = isset($row['vt_per_kg']) ? strval($row['vt_per_kg']) : null;
                $list_ventilator_value_for_bulk_insert['predicted_vt'][] = isset($row['predicted_vt']) ? strval($row['predicted_vt']) : null;
                $list_ventilator_value_for_bulk_insert['estimated_vt'][] = isset($row['estimated_vt']) ? strval($row['estimated_vt']) : null;
                $list_ventilator_value_for_bulk_insert['estimated_mv'][] = isset($row['estimated_mv']) ? strval($row['estimated_mv']) : null;
                $list_ventilator_value_for_bulk_insert['estimated_peep'][] = isset($row['estimated_peep']) ? strval($row['estimated_peep']) : null;
                $list_ventilator_value_for_bulk_insert['fio2'][] = isset($row['fio2']) ? strval($row['fio2']) : null;
                $list_ventilator_value_for_bulk_insert['status_use'][] = !empty($row['status_use']) ? intval($row['status_use']) : null;
                $list_ventilator_value_for_bulk_insert['status_use_other'][] = isset($row['status_use_other']) ? strval($row['status_use_other']) : null;
                $list_ventilator_value_for_bulk_insert['spo2'][] = isset($row['spo2']) ? strval($row['spo2']) : null;
                $list_ventilator_value_for_bulk_insert['etco2'][] = isset($row['etco2']) ? strval($row['etco2']) : null;
                $list_ventilator_value_for_bulk_insert['pao2'][] = isset($row['pao2']) ? strval($row['pao2']) : null;
                $list_ventilator_value_for_bulk_insert['paco2'][] = isset($row['paco2']) ? strval($row['paco2']) : null;
                $list_ventilator_value_for_bulk_insert['fixed_flg'][] = isset($row['fixed_flg']) ? intval($row['fixed_flg']) : null;
                $list_ventilator_value_for_bulk_insert['fixed_at'][] = !empty($row['fixed_at']) ? DateUtil::parseToDatetime($row['fixed_at']) : null;
                $list_ventilator_value_for_bulk_insert['confirmed_flg'][] = isset($row['confirmed_flg']) ? intval($row['confirmed_flg']) : null;
                $list_ventilator_value_for_bulk_insert['confirmed_at'][] = !empty($row['confirmed_at']) ? DateUtil::parseToDatetime($row['confirmed_at']) : null;
            }
        }

        return $list_ventilator_value_for_bulk_insert;
    }
}
