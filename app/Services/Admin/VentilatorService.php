<?php

namespace App\Services\Admin;

use App\Exceptions;
use App\Exceptions\InvalidCsvException;
use App\Exceptions\InvalidFormException;
use App\Http\Forms\Admin as Form;
use App\Http\Response as Response;
use App\Models\Patient;
use App\Repositories as Repos;
use App\Services\Support\Converter;
use App\Services\Support\CryptUtil;
use App\Services\Support\DateUtil;
use App\Services\Support\DBUtil;
use App\Services\Support\Logic\CsvLogic;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class VentilatorService
{
    use CsvLogic;

    function getVentilatorData($base_url, $form = null)
    {
        $items_per_page = config('view.items_per_page');

        $offset = 0;

        $search_values = [];

        if (!is_null($form)) {

            if (isset($form->page)) $offset = ($form->page - 1) * $items_per_page;

            $search_values = $this->buildVentilatorSearchValues($form);
        }

        $ventilators = Repos\VentilatorRepository::findBySearchValuesAndOffsetAndLimit($offset, $items_per_page, $search_values);

        $total_count = Repos\VentilatorRepository::countBySearchValues($search_values);

        $url = !is_null($form) ? $base_url . $form->http_query : $base_url;

        return Converter\VentilatorConverter::convertToAdminPagenate($ventilators, $total_count, $items_per_page, $url);
    }

    function getPatient(Form\VentilatorPatientForm $form)
    {
        $patient_code = Repos\VentilatorRepository::getPatientCodeById($form->id);

        return Converter\VentilatorConverter::convertToPatientResult($patient_code);
    }

    function create(Form\VentilatorCsvImportForm $form, string $file_url)
    {
        $organization_id = $form->organization_id;
        //新たに挿入されたventilator_idともともとのventilator_idのマッピング。
        $map_org_ventilator_id_to_new = [];
        //新たに挿入されたpatient_idともともとのpatient_idのマッピング
        $map_org_patient_id_to_new = [];


        //TODO:認証済みユーザーのid取得
        $registered_user_id = 1;

        try {
            $this->processCsvAfterValidateAll(
                $file_url,
                config('ventilator_csv.header'),
                config('ventilator_csv.validation_rule'),
                function ($rows) use ($organization_id, $registered_user_id, &$map_org_ventilator_id_to_new, &$map_org_patient_id_to_new, $form) {
                    //save対象のventilator列
                    $map_org_ventilator_id_to_ventilators = [];
                    //save対象のpatient列
                    $map_org_patient_id_to_patients = [];

                    //bulk insert対象のpatient_value情報
                    $insert_target_org_patient_id = [];
                    $insert_target_patient_value_registered_at = [];
                    $insert_target_opt_out_flg = [];
                    $insert_target_age = [];
                    $insert_target_vent_disease_name = [];
                    $insert_target_other_disease_name_1 = [];
                    $insert_target_other_disease_name_2 = [];
                    $insert_target_used_place = [];
                    $insert_target_hospital = [];
                    $insert_target_national = [];
                    $insert_target_discontinuation_at = [];
                    $insert_target_outcome = [];
                    $insert_target_treatment = [];
                    $insert_target_adverse_event_flg = [];
                    $insert_target_adverse_event_contents = [];

                    //bulk insert対象のventilator_value情報
                    $insert_target_org_ventilator_id = [];
                    $insert_target_appkey_id = [];
                    $insert_target_ventilator_value_registered_at = [];
                    $insert_target_height = [];
                    $insert_target_weight = [];
                    $insert_target_gender = [];
                    $insert_target_ideal_weight = [];
                    $insert_target_airway_pressure = [];
                    $insert_target_total_flow = [];
                    $insert_target_air_flow = [];
                    $insert_target_o2_flow = [];
                    $insert_target_rr = [];
                    $insert_target_expiratory_time = [];
                    $insert_target_inspiratory_time = [];
                    $insert_target_vt_per_kg = [];
                    $insert_target_predicted_vt = [];
                    $insert_target_estimated_vt = [];
                    $insert_target_estimated_mv = [];
                    $insert_target_estimated_peep = [];
                    $insert_target_fio2 = [];
                    $insert_target_status_use = [];
                    $insert_target_status_use_other = [];
                    $insert_target_spo2 = [];
                    $insert_target_etco2 = [];
                    $insert_target_pao2 = [];
                    $insert_target_paco2 = [];
                    $insert_target_fixed_flg = [];
                    $insert_target_fixed_at = [];
                    $insert_target_confirmed_flg = [];
                    $insert_target_confirmed_at = [];

                    foreach ($rows as $row) {
                        //呼吸器データ移行用準備
                        //現在行のventilatorが登録またはsave対象となっているかどうか。
                        if (!key_exists($row['ventilator_id'], $map_org_patient_id_to_new) && !key_exists($row['ventilator_id'], $map_org_ventilator_id_to_ventilators)) {
                            $ventilator_copy = Repos\VentilatorRepository::findOneById($row['ventilator_id'])->replicate();

                            if ($organization_id === $ventilator_copy->organization_id) {
                                //インポート先の組織に登録済みの場合はスキップ
                                continue;
                            }

                            $gs1_code = strval($row['gs1_code']);
                            $serial_number = strval($row['serial_number']);
                            $city = isset($row['city']) ? strval($row['city']) : null;
                            $qr_read_at = DateUtil::parseToDatetime($row['qr_read_at']);
                            $expiration_date = isset($row['expiration_date']) ? DateUtil::parseToDate($row['expiration_date']) : null;
                            $start_using_at = DateUtil::parseToDatetime($row['start_using_at']);

                            //save予約
                            $map_org_ventilator_id_to_ventilators[$row['ventilator_id']] = Converter\VentilatorConverter::convertToImportedVentilatorEntity($ventilator_copy, $organization_id, $registered_user_id, $gs1_code, $serial_number, $city, $qr_read_at, $expiration_date, $start_using_at);
                        }

                        //患者データ・患者観察研究データ移行(エクスポート/インポート対象の患者観察研究データは1患者につき最大1つ)
                        if ($row['patient_exists'] && !key_exists($row['patient_id'], $map_org_patient_id_to_new) && !key_exists($row['patient_id'], $map_org_patient_id_to_patients)) {

                            $patient_copy = Repos\PatientRepository::findOneById($row['patient_id'])->replicate();

                            if ($organization_id === $patient_copy->patient_id) {
                                //インポート先の組織に登録済みの場合はスキップ
                                continue;
                            }

                            $patient_code = ($row['patient_code'] !== '') ? strval($row['patient_code']) : null;
                            $patient_height = strval($row['patient_height']);
                            $patient_weight = strval($row['patient_weight']);
                            $patient_gender = intval($row['patient_gender']);

                            //移行先の組織に同一の患者コードが存在
                            $patient_code_duplicated = false;

                            if (!is_null($patient_code)) {

                                $patient_code_duplicated = Repos\PatientRepository::existsByPatientCodeAndOrganizationId($patient_code, $organization_id);
                            }

                            if ($patient_code_duplicated) {
                                throw new InvalidCsvException('validation.duplicated_patient_code_imported', compact('patient_code'));
                            }

                            $map_org_patient_id_to_patients[$row['patient_id']] = Converter\PatientConverter::convertToImportedPatientEntity($patient_copy, $organization_id, $patient_height, $patient_weight, $patient_gender, $patient_code);

                            //患者観察研究データ移行準備
                            if ($row['patient_value_exists']) {
                                $insert_target_org_patient_id[] = $row['patient_id'];
                                $insert_target_patient_value_registered_at[] = !empty($row['patient_value_registered_at']) ? DateUtil::parseToDatetime($row['patient_value_registered_at']) : null;
                                $insert_target_age[] = isset($row['age']) ? strval($row['age']) : null;
                                $insert_target_vent_disease_name[] = isset($row['vent_disease_name']) ? strval($row['vent_disease_name']) : null;
                                $insert_target_other_disease_name_1[] = isset($row['other_disease_name_1']) ? strval($row['other_disease_name_1']) : null;
                                $insert_target_other_disease_name_2[] = isset($row['other_disease_name_2']) ? strval($row['other_disease_name_2']) : null;
                                $insert_target_used_place[] = isset($row['used_place']) ? intval($row['used_place']) : null;
                                $insert_target_hospital[] = isset($row['hospital']) ? strval($row['hospital']) : null;
                                $insert_target_national[] = isset($row['national']) ? strval($row['national']) : null;
                                $insert_target_discontinuation_at[] = !empty($row['discontinuation_at']) ? DateUtil::parseToDatetime($row['discontinuation_at']) : null;
                                $insert_target_outcome[] = isset($row['outcome']) ? intval($row['outcome']) : null;
                                $insert_target_treatment[] = isset($row['treatment']) ? intval($row['treatment']) : null;
                                $insert_target_adverse_event_flg[] = isset($row['adverse_event_flg']) ? intval($row['adverse_event_flg']) : null;
                                $insert_target_adverse_event_contents[] = isset($row['adverse_event_contents']) ? strval($row['adverse_event_contents']) : null;
                                $insert_target_opt_out_flg[] = isset($row['opt_out_flg']) ? intval($row['opt_out_flg']) : null;
                            }
                        }

                        //機器観察研究データ移行準備
                        if ($row['ventilator_value_exists']) {
                            $insert_target_org_ventilator_id[] = $row['ventilator_id'];
                            $insert_target_appkey_id[] = isset($row['appkey_id']) ? strval($row['appkey_id']) : null;
                            $insert_target_ventilator_value_registered_at[] = !empty($row['ventilator_value_registered_at']) ? DateUtil::parseToDatetime($row['ventilator_value_registered_at']) : null;
                            $insert_target_height[] = isset($row['height']) ? strval($row['height']) : null;
                            $insert_target_weight[] = isset($row['weight']) ? strval($row['weight']) : null;
                            $insert_target_gender[] = isset($row['gender']) ? strval($row['gender']) : null;
                            $insert_target_ideal_weight[] = isset($row['ideal_weight']) ? strval($row['ideal_weight']) : null;
                            $insert_target_airway_pressure[] = isset($row['airway_pressure']) ? strval($row['airway_pressure']) : null;
                            $insert_target_total_flow[] = isset($row['total_flow']) ? strval($row['total_flow']) : null;
                            $insert_target_air_flow[] = isset($row['air_flow']) ? strval($row['air_flow']) : null;
                            $insert_target_o2_flow[] = isset($row['o2_flow']) ? strval($row['o2_flow']) : null;
                            $insert_target_rr[] = isset($row['rr']) ? strval($row['rr']) : null;
                            $insert_target_expiratory_time[] = isset($row['expiratory_time']) ? strval($row['expiratory_time']) : null;
                            $insert_target_inspiratory_time[] = isset($row['inspiratory_time']) ? strval($row['inspiratory_time']) : null;
                            $insert_target_vt_per_kg[] = isset($row['vt_per_kg']) ? strval($row['vt_per_kg']) : null;
                            $insert_target_predicted_vt[] = isset($row['predicted_vt']) ? strval($row['predicted_vt']) : null;
                            $insert_target_estimated_vt[] = isset($row['estimated_vt']) ? strval($row['estimated_vt']) : null;
                            $insert_target_estimated_mv[] = isset($row['estimated_mv']) ? strval($row['estimated_mv']) : null;
                            $insert_target_estimated_peep[] = isset($row['estimated_peep']) ? strval($row['estimated_peep']) : null;
                            $insert_target_fio2[] = isset($row['fio2']) ? strval($row['fio2']) : null;
                            $insert_target_status_use[] = isset($row['status_use']) ? intval($row['status_use']) : null;
                            $insert_target_status_use_other[] = isset($row['status_use_other']) ? strval($row['status_use_other']) : null;
                            $insert_target_spo2[] = isset($row['spo2']) ? strval($row['spo2']) : null;
                            $insert_target_etco2[] = isset($row['etco2']) ? strval($row['etco2']) : null;
                            $insert_target_pao2[] = isset($row['pao2']) ? strval($row['pao2']) : null;
                            $insert_target_paco2[] = isset($row['paco2']) ? strval($row['paco2']) : null;
                            $insert_target_fixed_flg[] = isset($row['fixed_flg']) ? intval($row['fixed_flg']) : null;
                            $insert_target_fixed_at[] = !empty($row['fixed_at']) ? DateUtil::parseToDatetime($row['fixed_at']) : null;
                            $insert_target_confirmed_flg[] = isset($row['confirmed_flg']) ? intval($row['confirmed_flg']) : null;
                            $insert_target_confirmed_at[] = !empty($row['confirmed_at']) ? DateUtil::parseToDatetime($row['confirmed_at']) : null;
                        }
                    }

                    //////////////////


                    DBUtil::Transaction(
                        'CSVインポート',
                        function () use (
                            $map_org_ventilator_id_to_ventilators,
                            $map_org_patient_id_to_patients,
                            $insert_target_org_patient_id,
                            $insert_target_patient_value_registered_at,
                            $insert_target_opt_out_flg,
                            $insert_target_age,
                            $insert_target_vent_disease_name,
                            $insert_target_other_disease_name_1,
                            $insert_target_other_disease_name_2,
                            $insert_target_used_place,
                            $insert_target_hospital,
                            $insert_target_national,
                            $insert_target_discontinuation_at,
                            $insert_target_outcome,
                            $insert_target_treatment,
                            $insert_target_adverse_event_flg,
                            $insert_target_adverse_event_contents,
                            $insert_target_org_ventilator_id,
                            $insert_target_appkey_id,
                            $insert_target_ventilator_value_registered_at,
                            $insert_target_height,
                            $insert_target_weight,
                            $insert_target_gender,
                            $insert_target_ideal_weight,
                            $insert_target_airway_pressure,
                            $insert_target_total_flow,
                            $insert_target_air_flow,
                            $insert_target_o2_flow,
                            $insert_target_rr,
                            $insert_target_expiratory_time,
                            $insert_target_inspiratory_time,
                            $insert_target_vt_per_kg,
                            $insert_target_predicted_vt,
                            $insert_target_estimated_vt,
                            $insert_target_estimated_mv,
                            $insert_target_estimated_peep,
                            $insert_target_fio2,
                            $insert_target_status_use,
                            $insert_target_status_use_other,
                            $insert_target_spo2,
                            $insert_target_etco2,
                            $insert_target_pao2,
                            $insert_target_paco2,
                            $insert_target_fixed_flg,
                            $insert_target_fixed_at,
                            $insert_target_confirmed_flg,
                            $insert_target_confirmed_at,
                            $registered_user_id,
                            &$map_org_ventilator_id_to_new,
                            &$map_org_patient_id_to_new,
                        ) {
                            $update_target_ventilator_id = [];
                            $update_target_ventilator_patient_id = [];

                            if (!empty($map_org_ventilator_id_to_ventilators)) {
                                //insertするventilatorごとの新旧idの紐付けを行いたいので個別saveとする。
                                foreach ($map_org_ventilator_id_to_ventilators as $org_ventilator_id => $ventilator) {
                                    $ventilator->save();



                                    //patient insert後に対応ventialtor.patient_idをbulk update
                                    if (!is_null($ventilator->patient_id)) {
                                        $update_target_ventilator_id[] = $ventilator->id;
                                        $update_target_ventilator_patient_id[] = $ventilator->patient_id;
                                    }

                                    $map_org_ventilator_id_to_new[$org_ventilator_id] = $ventilator->id;
                                }
                            }

                            if (!empty($map_org_patient_id_to_patients)) {
                                //ventilator同様、新旧idの紐付けのため個別にsave
                                foreach ($map_org_patient_id_to_patients as $org_patient_id => $patient) {

                                    $patient->save();

                                    $map_org_patient_id_to_new[$org_patient_id] = $patient->id;
                                }

                                //以下バルクupdate/insert処理
                                //ventilator bulk update
                                $update_target_ventilator_patient_id = array_map(
                                    function ($org_patient_id) use ($map_org_patient_id_to_new) {
                                        return $map_org_patient_id_to_new[$org_patient_id];
                                    },
                                    $update_target_ventilator_patient_id
                                );

                                Repos\VentilatorRepository::updateBulkForPatientId(
                                    $update_target_ventilator_id,
                                    $update_target_ventilator_patient_id
                                );
                            }

                            if (!empty($insert_target_org_patient_id)) {
                                $insert_target_patient_id = array_map(
                                    function ($org_patient_id) use ($map_org_patient_id_to_new) {
                                        return $map_org_patient_id_to_new[$org_patient_id];
                                    },
                                    $insert_target_org_patient_id
                                );


                                Repos\PatientValueRepository::insertBulk(
                                    $insert_target_patient_id,
                                    $insert_target_patient_value_registered_at,
                                    $insert_target_opt_out_flg,
                                    $insert_target_age,
                                    $insert_target_vent_disease_name,
                                    $insert_target_other_disease_name_1,
                                    $insert_target_other_disease_name_2,
                                    $insert_target_used_place,
                                    $insert_target_hospital,
                                    $insert_target_national,
                                    $insert_target_discontinuation_at,
                                    $insert_target_outcome,
                                    $insert_target_treatment,
                                    $insert_target_adverse_event_flg,
                                    $insert_target_adverse_event_contents,
                                    $registered_user_id
                                );
                            }

                            if (!empty($insert_target_org_ventilator_id)) {
                                $insert_target_ventilator_id = array_map(
                                    function ($org_ventilator_id) use ($map_org_ventilator_id_to_new) {
                                        return $map_org_ventilator_id_to_new[$org_ventilator_id];
                                    },
                                    $insert_target_org_ventilator_id
                                );

                                Repos\VentilatorValueRepository::insertBulk(
                                    $insert_target_ventilator_id,
                                    $insert_target_appkey_id,
                                    $insert_target_ventilator_value_registered_at,
                                    $insert_target_height,
                                    $insert_target_weight,
                                    $insert_target_gender,
                                    $insert_target_ideal_weight,
                                    $insert_target_airway_pressure,
                                    $insert_target_total_flow,
                                    $insert_target_air_flow,
                                    $insert_target_o2_flow,
                                    $insert_target_rr,
                                    $insert_target_expiratory_time,
                                    $insert_target_inspiratory_time,
                                    $insert_target_vt_per_kg,
                                    $insert_target_predicted_vt,
                                    $insert_target_estimated_vt,
                                    $insert_target_estimated_mv,
                                    $insert_target_estimated_peep,
                                    $insert_target_fio2,
                                    $insert_target_status_use,
                                    $insert_target_status_use_other,
                                    $insert_target_spo2,
                                    $insert_target_etco2,
                                    $insert_target_pao2,
                                    $insert_target_paco2,
                                    $insert_target_fixed_flg,
                                    $insert_target_fixed_at,
                                    $insert_target_confirmed_flg,
                                    $insert_target_confirmed_at,
                                    $registered_user_id
                                );
                            }
                        }
                    );
                }
            );
        } catch (InvalidCsvException $e) {
            $error_message = $e->getMessage();
            $form->addError('csv_file', $error_message);
            throw new InvalidFormException($form);
        }

        return new Response\SuccessJsonResult;
    }

    function update(Form\VentilatorUpdateForm $form)
    {
        $ventilator = Repos\VentilatorRepository::findOneById($form->id);

        $entity = Converter\VentilatorConverter::convertToAdminVentilatorUpdateEntity($ventilator, $form->start_using_at);

        DBUtil::Transaction(
            'MicroVent編集',
            function () use ($entity) {
                $entity->save();
            }
        );

        return new Response\SuccessJsonResult;
    }

    function delete(Form\VentilatorDeleteForm $form)
    {
        $ids = $form->ids;

        DBUtil::Transaction(
            'MicroVent削除',
            function () use ($ids) {
                Repos\VentilatorRepository::deleteByIds($ids);
            }
        );

        return new Response\SuccessJsonResult;
    }

    function buildVentilatorSearchValues(Form\VentilatorSearchForm $form)
    {
        $search_values = [];

        if (isset($form->serial_number)) $search_values['serial_number'] = $form->serial_number;
        if (isset($form->organization_name)) $search_values['organization_name'] = $form->organization_name;
        if (isset($form->registered_user_name)) $search_values['registered_user_name'] = $form->registered_user_name;
        if (isset($form->expiration_date_from)) $search_values['expiration_date_from'] = $form->expiration_date_from;
        if (isset($form->expiration_date_to)) $search_values['expiration_date_to'] = $form->expiration_date_to;
        if (isset($form->start_using_at_from)) $search_values['start_using_at_from'] = $form->start_using_at_from;
        if (isset($form->start_using_at_to)) $search_values['start_using_at_to'] = $form->start_using_at_to;
        if (isset($form->has_bug)) $search_values['has_bug'] = $form->has_bug;

        return $search_values;
    }

    function getBugsList(Form\VentilatorBugsForm $form)
    {
        $bugs = Repos\VentilatorBugRepository::findByVentilatorId($form->id);

        return array_map(
            function ($user) {
                return Converter\VentilatorConverter::convertToBugsListElmEntity($user);
            },
            $bugs->all()
        );
    }

    function createVentilatorCsv(Form\VentilatorCsvExportForm $form)
    {
        $query = Repos\VentilatorRepository::queryForCreateVentialtorCsvByids($form->ids);

        $filename = config('ventilator_csv.filename');

        $header = config('ventilator_csv.header');

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
        );
    }

    function buildVentilatorCsvRow($entity)
    {
        $row = [];

        $header = config('ventilator_csv.header');

        foreach ($header as $key => $val) {
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
}
