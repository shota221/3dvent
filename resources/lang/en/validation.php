<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute must be accepted.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute may only contain letters.',
    'alpha_dash' => 'The :attribute may only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute may only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'The :attribute must be a valid email address.',
    'ends_with' => 'The :attribute must end with one of the following: :values.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file' => 'The :attribute must be greater than or equal :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => 'The :attribute must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'The :attribute must be an integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file' => 'The :attribute must be less than or equal :value kilobytes.',
        'string' => 'The :attribute must be less than or equal :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file' => 'The :attribute may not be greater than :max kilobytes.',
        'string' => 'The :attribute may not be greater than :max characters.',
        'array' => 'The :attribute may not have more than :max items.',
    ],
    'mimes' => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => 'The :attribute must be at least :min characters.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'multiple_of' => 'The :attribute must be a multiple of :value.',
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => 'The :attribute must be a number.',
    'password' => 'The password is incorrect.',
    'present' => 'The :attribute field must be present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => 'The :attribute field is required.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values.',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid zone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute format is invalid.',
    'uuid' => 'The :attribute must be a valid UUID.',


    /**
     * カスタム
     */
    'account_not_found'                   => 'The account name has been deleted or does not exist.',
    'account_or_password_incorrect'       => 'Account name or password is incorrect.',
    'appkey_not_found'                    => 'This is a non-existent appkey.',
    'appkey_required'                     => 'Appkey is required.',
    'auth_token_expired'                  => 'Auth token has expired.',
    'code_not_found'                      => "That organization code doesn't exist.",
    'csv_duplicated_patient_code'         => 'File loading canceled. Patient number :patient_code has already been used by the specified organization. Please rewrite the patient number.',
    'csv_duplicated_row'                  => 'File loading canceled. Duplicate :attribute in line :error_row',
    'csv_header_error'                    => 'File loading canceled. There is an error in the name of the header in the first line. Prease match it to the header name in the output CSV.',
    'csv_registered_user_email'           => 'File loading canceled. It contains an email address that is already registered.',
    'csv_registered_user_name'            => 'File loading canceled. It contains an user name that is already registered.',
    'csv_required'                        => 'Only csv format is valid.',
    'csv_row_error'                       => 'File loading canceled. There is an input error in line :row_nums.',
    'csv_too_many_rows'                   => 'The number of lines in the file is too large, please make it less than :row_count_limit lines.',
    'duplicated_email_registration'       => 'This email address has already been registered. Please use a different email address.',
    'duplicated_patient_code'             => 'This patient number has already been registered with your organization.',
    'duplicated_patient_id'               => 'This ID has already been registered.',
    'duplicated_registration'             => "It's already registered.",
    'duplicated_user_name'                => 'This user name has already been registered with your organization.',
    'excessive_number_of_deletions'       => 'There are too many deletions.',
    'excessive_number_of_registrations'   => 'There are too many registrations.',
    'id_inaccessible'                     => 'You are not authorized to handle that ID.',
    'id_not_found_contained'              => 'Contains a non-existent ID.',
    'id_not_found'                        => 'This is a non-existent ID.',
    'invalid_sound'                       => 'Measurement failed due to excessive noise. Please switch to manual measurement.',
    'not_enough_pulses'                   => 'The measurement failed because not enough signal was detected. Please move your device closer to the ventilator and record again.',
    'not_enough_recording_time'           => 'Measurement failed due to insufficient recording time. Please record again.',
    'organization_mismatch'               => 'Inconsistent organization information.',
    'password_confirmed'                  => 'The password confirmation does not match.',
    // TODO　新潟病院から言語定義共有後設定
    // 'required_for_principal_investigator' => '医師（施設内研究代表者）の場合メールアドレスは必須です。',
    'required_password_confirmation'      => 'The password field is required.',
    'required_password'                   => 'The confirm password field is required.',
    'unauthenticated'                     => 'Authentication Error.',
    'user_token_required'                 => 'User token is required.',
    // TODO　新潟病院から言語定義共有後設定
    // 'ventilator_value_exists_yet'         => 'MicroVent機器の削除は、対象の機器が紐づく機器設定・測定データすべて削除してから実行してください。', 

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    //　TODO　新潟病院から言語定義共有後設定
    'attributes' => [
        'admin_authority_type'           => "Authority type",
        // 'adverse_event_contents'         => '機器に関する有害事象の内容',
        // 'adverse_event_flg'              => '機器に関する有害事象',
        // 'age'                            => '年齢',
        'air_flow'                       => 'Air flow rate',
        'airway_pressure'                => 'Pressure Dial setting',
        'authority'                      => 'Authority',
        // 'bug_name'                       => '不具合名',
        'code'                           => 'Organization code',
        // 'discontinuation_at'             => '使用中止日時',
        'e'                              => 'Exhalation',
        'e_avg'                          => 'Average exhalation time',
        // 'edcid'                          => 'EDC施設ID',
        'email'                          => 'Email address',
        // 'etco2'                          => '呼気終末二酸化炭素分圧',
        'gender'                         => 'Gender',
        'height'                         => 'Height',
        // 'hospital'                       => '病院名',
        'i'                              => 'Inhalation',
        'i_avg'                          => 'Avarage inhalation time',
        'language_code'                  => 'Language',
        'name'                           => 'Account name',
        // 'national'                       => '国名',
        'o2_flow'                        => 'Oxygen flow rate',
        // 'opt_out_flg'                    => 'オプトアウト',
        'org_authority_type'             => "Authority type",
        'organization_code'              => 'Organization code',
        'organization_name'              => 'Organization name',
        // 'other_disease_name_1'           => 'その他疾患名1',
        // 'other_disease_name_2'           => 'その他疾患名2',
        // 'outcome'                        => '使用中止時の転帰',
        'password'                       => 'Password',
        'password_confirmation'          => 'Confirm Password',
        'patient_code'                   => 'Patient number',
        // 'patient_observation_status'     => '患者観察研究ステータス',
        'representative_email'           => 'Representative email address',
        'representative_name'            => 'Representative name',
        // 'request_improvement'            => '改善要望',
        'respirations_per_10sec'         => 'Respiration times/10sec',
        // 'spo2'                           => '経皮酸素飽和度',
        // 'start_using_at'                 => '使用開始日時',
        // 'status_use_other'               => '使用状況（その他の場合）',
        // 'status_use'                     => '使用状況',
        'status'                         => 'Status',
        // 'treatment'                      => '使用中止後の呼吸不全治療',
        // 'used_place'                     => '使用場所',
        'user_name'                      => 'User name',
        // 'vent_disease_name'              => 'Microventを使用した原因病名',
        'ventilator_value_scan_interval' => 'Fixed Value Determination Time',
        'vt_per_kg'                      => 'Recommended tidal volume per kg of ideal body weight',
        'weight'                         => 'Weight',
    ],

];
