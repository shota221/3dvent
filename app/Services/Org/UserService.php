<?php

namespace App\Services\Org;

use App\Exceptions;
use App\Http\Forms\Org as Form;
use App\Http\Response;
use App\Repositories as Repos;
use App\Services\Support\Converter;
use App\Services\Support\CryptUtil;
use App\Services\Support\DBUtil;
use App\Services\Support\FileUtil;
use App\Services\Support\Logic;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Log;

class UserService
{
    use Logic\CsvLogic;

    /**
     * ユーザー一覧取得(ページネーション)
     *
     * @param              string $path
     * @param Form\UserSearchForm $form
     * @return [type]
     */
    public function getPaginatedUserData(
        string $path,
        int $organization_id,
        Form\UserSearchForm $form = null)
    {
        $limit         = config('view.items_per_page');
        $offset        = 0;
        $search_values = [];
        $http_query    = '';

        if (! is_null($form)) {
            if (isset($form->page)) $offset = ($form->page - 1) * $limit;
            $search_values = $this->buildUserSearchValues($form);
            $http_query = '?' . http_build_query($search_values);
        }
        
        $users = Repos\UserRepository::searchByOrganizationId(
            $search_values,
            $organization_id, 
            $limit, 
            $offset);

        $total_count = Repos\UserRepository::countByOrganizationIdAndSearchValues($organization_id, $search_values);
        
        $item_per_page = $limit;

        return Converter\UserConverter::convertToPaginatedUserData(
            $users, 
            $total_count, 
            $item_per_page, 
            $path . $http_query);
    }

    /**
     * ユーザーアカウント取得
     *
     * @param Form\UserDetailForm $form
     * @return type
     */
    public function getOneUserData(
        Form\UserDetailForm $form, 
        int $organization_id)
    {
        // ログインユーザーの所属組織に属するユーザー取得
        $user = Repos\UserRepository::findOneByOrganizationIdAndId($organization_id, $form->id);

        if (is_null($user)) {
            $form->addError('id', 'validation.id_not_found');
            throw new Exceptions\InvalidFormException($form);
        }

        return Converter\UserConverter::convertToUserData($user);
    }

    /**
     * ユーザーアカウント修正
     *
     * @param Form\UserUpdateForm $form
     * @return type
     */
    public function update(
        Form\UserUpdateForm $form, 
        int $organization_id, 
        int $user_id)
    {
        // ログインユーザーの所属組織に属するユーザー取得
        $user = Repos\UserRepository::findOneByOrganizationIdAndId($organization_id, $form->id);

        if (is_null($user)) {
            $form->addError('id', 'validation.id_not_found');
            throw new Exceptions\InvalidFormException($form);
        }

        // 組織内ユーザー名重複チェック
        $registered_user = Repos\UserRepository::findOnebyOrganizationIdAndName($organization_id, $form->name);

        $is_duplicated_name = ! is_null($registered_user) && $registered_user->id !== $user->id;

        if ($is_duplicated_name) {
            $form->addError('name', 'validation.duplicated_registration');
            throw new Exceptions\InvalidFormException($form);
        }

        // メールの入力がある場合は組織内重複チェック
        if (! empty($form->email)) {
            $registered_user = Repos\UserRepository::findOneByOrganizationIdAndEmail($user->organization_id, $form->email);

            $is_duplicated_email = ! is_null($registered_user) && $registered_user->id !== $user->id;
    
            if ($is_duplicated_email) {
                $form->addError('email', 'validation.duplicated_registration');
                throw new Exceptions\InvalidFormException($form);
            }
        }
     
        // 更新データのセット
        $user->updated_user_id     = $user_id;
        $user->name                = $form->name;
        $user->email               = $form->email;
        $user->org_authority_type  = $form->org_authority_type;
        $user->authority           = Converter\Type\Authority::convertToOrgAuthority($form->org_authority_type);
        $user->disabled_flg        = $form->disabled_flg;
        if (! is_null($form->password)) $user->password = Hash::make($form->password);
        
        DBUtil::Transaction(
            'ユーザー編集',
            function () use ($user) {
                $user->save();
            }
        );
        
        return new Response\SuccessJsonResult;
    }

    /**
     * ユーザーアカウント登録
     *
     * @param Form\UserCreateForm $form
     * @return type
     */
    public function create(
        Form\UserCreateForm $form, 
        int $organization_id, 
        int $user_id)
    {
        $exists_name = Repos\UserRepository::existsByNameAndOrganizationId($form->name, $organization_id);

        if ($exists_name) {
            $form->addError('name', 'validation.duplicated_registration');
            throw new Exceptions\InvalidFormException($form);
        }
   
        if (! empty($form->email)) {
            $exists_email = Repos\UserRepository::existsByEmailAndOrganizationId($form->email, $organization_id);
    
            if ($exists_email) {
                $form->addError('email', 'validation.duplicated_registration');
                throw new Exceptions\InvalidFormException($form);
            }
        }

        $entity = Converter\UserConverter::convertToEntity(
            $organization_id,
            $user_id,
            $form->name,
            $form->email,
            $form->org_authority_type,
            Converter\Type\Authority::convertToOrgAuthority($form->org_authority_type),
            $form->disabled_flg,
            CryptUtil::createHashedPassword($form->password));

        
        DBUtil::Transaction(
            'ユーザーアカウント登録',
            function () use ($entity) {
                $entity->save();
            }
        );

        return new Response\SuccessJsonResult;
    }

    /**
     * ユーザー論理削除
     *
     * @param Form\logicalDelete $form
     * @return void
     */
    public function logicalDelete(
        Form\UserLogicalDeleteForm $form, 
        int $organization_id, 
        int $user_id)
    {
        $ids = $form->ids;

        $deletable_row_limit = 50; // ページネーション表示件数

        if (count($ids) > $deletable_row_limit) {
            $form->addError('ids', 'validation.excessive_number_of_registrations');
            throw new Exceptions\InvalidFormException($form);
        }

        // 削除済み、または不正なリクエストidを考慮しid再取得
        $target_ids = Repos\UserRepository::getIdsByOrganizationIdAndIds($organization_id, $ids);
        
        if (! empty($target_ids)) {
            DBUtil::Transaction(
                'ユーザー論理削除',
                function () use ($target_ids, $user_id) {
                     Repos\UserRepository::logicalDeleteByIds($target_ids->all(), $user_id);
                }
            );
        }

        return new Response\SuccessJsonResult;
    }

    /**
     * CSVフォーマット作成
     *
     * @return void
     */
    public function createUserCsvFormat()
    {
        $header_items  = array_values(config('user_csv.header'));

        $header = [];

        // header_itemから多言語化用ヘッダー作成
        foreach ($header_items as $header_item)
        {
           array_push($header, __('messages.' . $header_item));
        }

    
        $example = config('user_csv.example');

        $this->createCsvFormat($header, $example);
    }

    /**
     * ユーザーアカウントcsv一括登録
     *
     * @param Form\UserCsvImportForm $form
     * @return type
     */
    public function createByCsv(
        Form\UserCsvImportForm $form, 
        int $organization_id, 
        int $user_id)
    {
        $file                             = $form->csv_file;
        $path                             = FileUtil::getUploadedFilePath($file);
        $row_count                        = count(file($path)) - 1; //ヘッダー行含めない 
        $creatale_row_limit               = 100;  // 一括登録最大件数 
        $map_attribute_to_header_items    = config('user_csv.header');
        $map_attribute_to_validation_rule = config('user_csv.validation_rule');
        $dupulicate_confirmation_targets  = config('user_csv.dupulicate_confirmation_targets'); 

        if ($row_count > $creatale_row_limit) {
            $form->addError('csv_file', 'validation.excessive_number_of_registrations');
            throw new Exceptions\InvalidFormException($form);
        }

        $map_attribute_to_header = [];

        // 多言語化用ヘッダー作成
        foreach ($map_attribute_to_header_items as $key => $value)
        {
            $map_attribute_to_header[$key] = __('messages.'. $value);
        }

        try {
            $this->processCsv(
                $path,
                $map_attribute_to_header,
                $map_attribute_to_validation_rule, 
                $dupulicate_confirmation_targets,
                function ($rows) use ($form, $organization_id, $user_id) {

                    $names                = array_map(function ($row) { return $row['name']; }, $rows);
                    $emails               = array_map(function ($row) { return $row['email']; }, $rows);
                    $org_authority_types  = array_map(function ($row) { return $row['org_authority_type']; }, $rows);
                    $authorities          = array_map(function ($row) { 
                        return Converter\Type\Authority::convertToOrgAuthority($row['org_authority_type']); 
                    }, $rows);
                    
                    $hashed_passwords = array_map(function ($row) { return CryptUtil::createHashedPassword($row['password']); }, $rows);

                    $exists_name = Repos\UserRepository::existsByOrganizationIdAndNames($organization_id, $names);

                    if ($exists_name) {
                        throw new Exceptions\InvalidException('validation.csv_registered_user_name');
                    }

                    if (! empty($emails)) {
                        $exists_email = Repos\UserRepository::existsByOrganizationIdAndEmails($organization_id, $emails);
                
                        if ($exists_email) {
                            throw new Exceptions\InvalidException('validation.csv_registered_user_email');
                        }
                    }

                    DBUtil::Transaction(
                        'ユーザーアカウント登録',
                        function () use (
                            $organization_id, 
                            $user_id,
                            $names,
                            $emails,
                            $org_authority_types,
                            $authorities,
                            $hashed_passwords) {

                            Repos\UserRepository::insertBulk(
                                $organization_id, 
                                $user_id,
                                $names,
                                $emails,
                                $org_authority_types,
                                $authorities,
                                $hashed_passwords);
                        }
                    );
                }
            );

        } catch (Exceptions\InvalidCsvException $e) {
            $error_message = $e->getMessage() . $e->finishedRowCountMessage;
            $form->addError('csv_file', $error_message);

            throw new Exceptions\InvalidFormException($form);
        }

        return new Response\SuccessJsonResult;
    }

    private function buildUserSearchValues(Form\UserSearchForm $form)
    {
        $search_values      = [];
        $name               = $form->name;
        $org_authority_type = $form->org_authority_type;
        $registered_at_from = $form->registered_at_from;
        $registered_at_to   = $form->registered_at_to;
        $disabled_flg       = $form->disabled_flg;
        
        if (isset($name))               $search_values['name']               = $name;
        if (isset($org_authority_type)) $search_values['org_authority_type'] = $org_authority_type;
        if (isset($registered_at_from)) $search_values['registered_at_from'] = $registered_at_from;
        if (isset($registered_at_to))   $search_values['registered_at_to']   = $registered_at_to;
        if (isset($disabled_flg))       $search_values['disabled_flg']       = $disabled_flg;

        return $search_values;
    }
}