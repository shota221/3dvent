<?php

use App\Models;

return [

    /*
      |--------------------------------------------------------------------------
      | Validation Language Lines
      | 検証言語
      |--------------------------------------------------------------------------
      |
      | The following language lines contain the default error messages used by
      | the validator class. Some of these rules have multiple versions such
      | as the size rules. Feel free to tweak each of these messages here.
      |
      | 次の言語行には、バリデータークラスで使用されるデフォルトのエラーメッセージが含まれています。
      | これらの規則の中には、サイズ規則などの複数のバージョンがあります。
      | これらのメッセージのそれぞれをここで微調整してください。
    */

    'accepted'             => ':attribute が未承認です',
    'active_url'           => ':attribute は有効なURLではありません',
    'after'                => ':attribute は :date より後の日付にしてください',
    'after_or_equal'       => ':attribute は :date 以降の日付にしてください',
    'alpha'                => ':attribute は英字のみ有効です',
    'alpha_dash'           => ':attribute は「英字」「数字」「-(ダッシュ)」「_(下線)」のみ有効です',
    'alpha_num'            => ':attribute は「英字」「数字」のみ有効です',
    'array'                => ':attribute は配列タイプのみ有効です',
    'before'               => ':attribute は :date より前の日付にしてください',
    'before_or_equal'      => ':attribute は :date 以前の日付にしてください',
    'between'              => [
        'numeric' => ':attribute は :min ～ :max までの数値まで有効です',
        'file'    => ':attribute は :min ～ :max キロバイトまで有効です',
        'string'  => ':attribute は :min ～ :max 文字まで有効です',
        'array'   => ':attribute は :min ～ :max 個まで有効です',
    ],
    'boolean'              => ':attribute の値は true もしくは false のみ有効です',
    'confirmed'            => ':attribute を確認用と一致させてください',
    'date'                 => ':attribute を有効な日付形式にしてください',
    'date_format'          => ':attribute を :format 書式と一致させてください',
    'different'            => ':attribute を :other と違うものにしてください',
    'digits'               => ':attribute は :digits 桁の整数のみ有効です',
    'digits_between'       => ':attribute は :min ～ :max 桁のみ有効です',
    'dimensions'           => ':attribute ルールに合致する画像サイズのみ有効です',
    'distinct'             => ':attribute に重複している値があります',
    'email'                => ':attribute の書式のみ有効です',
    'exists'               => ':attribute 無効な値です',
    'file'                 => ':attribute アップロード出来ないファイルです',
    'filled'               => ':attribute 値を入力してください',
    'gt'                   => [
        'numeric' => ':attribute は :value より大きい必要があります。',
        'file'    => ':attributeは :value キロバイトより大きい必要があります。',
        'string'  => ':attribute は :value 文字より多い必要があります。',
        'array'   => ':attribute には :value 個より多くの項目が必要です。',
    ],
    'gte'                  => [
        'numeric' => ':attribute は :value 以上である必要があります。',
        'file'    => ':attribute は :value キロバイト以上である必要があります。',
        'string'  => ':attribute は :value 文字以上である必要があります。',
        'array'   => ':attribute には value 個以上の項目が必要です。',
    ],
    'image'                => ':attribute 画像は「jpg」「png」「bmp」「gif」「svg」のみ有効です',
    'in'                   => ':attribute 無効な値です',
    'in_array'             => ':attribute は :other と一致する必要があります',
    'integer'              => ':attribute は整数のみ有効です',
    'ip'                   => ':attribute IPアドレスの書式のみ有効です',
    'ipv4'                 => ':attribute IPアドレス(IPv4)の書式のみ有効です',
    'ipv6'                 => ':attribute IPアドレス(IPv6)の書式のみ有効です',
    'json'                 => ':attribute 正しいJSON文字列のみ有効です',
    'lt'                   => [
        'numeric' => ':attribute は :value 未満である必要があります。',
        'file'    => ':attribute は :value キロバイト未満である必要があります。',
        'string'  => ':attribute は :value 文字未満である必要があります。',
        'array'   => ':attribute は :value 未満の項目を持つ必要があります。',
    ],
    'lte'                  => [
        'numeric' => ':attribute は :value 以下である必要があります。',
        'file'    => ':attribute は :value キロバイト以下である必要があります。',
        'string'  => ':attribute は :value 文字以下である必要があります。',
        'array'   => ':attribute は :value 以上の項目を持つ必要があります。',
    ],
    'max'                  => [
        'numeric' => ':attribute は :max 以下のみ有効です',
        'file'    => ':attribute は :max KB以下のファイルのみ有効です',
        'string'  => ':attribute は :max 文字以下のみ有効です',
        'array'   => ':attribute は :max 個以下のみ有効です',
    ],
    'mimes'                => ':attribute は :values タイプのみ有効です',
    'mimetypes'            => ':attribute は :values タイプのみ有効です',
    'min'                  => [
        'numeric' => ':attribute は :min 以上のみ有効です',
        'file'    => ':attribute は :min KB以上のファイルのみ有効です',
        'string'  => ':attribute は :min 文字以上のみ有効です',
        'array'   => ':attribute は :min 個以上のみ有効です',
    ],
    'not_in'               => ':attribute 無効な値です',
    'not_regex'            => 'The :attribute format is invalid.',
    'numeric'              => ':attribute は数字のみ有効です',
    'present'              => ':attribute が存在しません',
    'regex'                => ':attribute は無効な値です',
    'required'             => ':attribute は必須です',
    'required_if'          => ':other が :value の場合、:attribute は必須です',
    'required_unless'      => ':attribute は :other が :values でなければ必須です',
    'required_with'        => ':attribute は :values が入力されている場合は必須です',
    'required_with_all'    => ':attribute は :values が入力されている場合は必須です',
    'required_without'     => ':attribute は :values が入力されていない場合は必須です',
    'required_without_all' => ':attribute は :values が入力されていない場合は必須です',
    'same'                 => ':attribute は :other と同じ場合のみ有効です',
    'size'                 => [
        'numeric' => ':attribute は :size のみ有効です',
        'file'    => ':attribute は :size KBのみ有効です',
        'string'  => ':attribute は :size 文字のみ有効です',
        'array'   => ':attribute は :size 個のみ有効です',
    ],
    'string'               => ':attribute は文字列のみ有効です',
    'timezone'             => ':attribute 正しいタイムゾーンのみ有効です',
    'unique'               => ':attribute は既に存在します',
    'uploaded'             => ':attribute アップロードに失敗しました',
    'url'                  => ':attribute は正しいURL書式のみ有効です',


    /**
     * カスタム
     */
    'account_not_found' => '削除されているか、存在しないアカウント名です。',
    'appkey_not_found' => '存在しないアプリキーです。',
    'appkey_required' => 'アプリキーは必須です。',
    'auth_token_expired' => 'トークン有効期限切れ',
    'csv_required' => 'csv形式のみ有効です',
    'csv_header_error' => '1行目の項目名に誤りがあるため読み込みをキャンセルしました。。エクスポートCSVの項目名に合わせてください。',
    'csv_row_error' => ':row_nums行目の入力に誤りがあるため読み込みをキャンセルしました。。',
    'csv_duplicated_row' => ':error_row 行目の :attribute が重複しているため読み込みをキャンセルしました。',
    'csv_registered_user_name' => '登録済みのユーザー名が存在しているため読み込みをキャンセルしました。',
    'duplicated_email_registration' => 'すでに登録されているメールアドレスです。別のメールアドレスを利用してください。',
    'duplicated_patient_code_imported' => ':patient_codeはインポート先組織ですでに使われている患者コードです。CSVを編集し、新しい患者コードに書き換えてください。',
    'duplicated_patient_code' => '同一組織内ですでに使われている患者番号です。',
    'duplicated_patient_id' => 'すでに登録済みです。',
    'duplicated_registration' => 'すでに登録されています。',
    'id_not_found'=>'削除されているか、存在しないIDです。',
    'code_not_found'=>'削除されているか、存在しない組織コードです。',
    'account_not_found'=>'削除されているか、存在しないアカウント名です。',
    'account_or_password_incorrect'=>'アカウント名またはパスワードが違います。',
    'appkey_not_found'=>'存在しないアプリキーです。',
    'appkey_required'=>'アプリキーは必須です。',
    'not_enough_recording_time'=>'録音時間が短すぎます。再度録音してください。',
    'not_enough_pulses'=>'十分なパルスが検出できませんでした。呼吸器に近づけて再度録音してください。',
    'invalid_sound'=>'雑音が大きいため測定できませんでした。手動測定に切り替えてください。',
    'duplicated_user_name' => '同一組織内ですでに使われているユーザー名です。',
    'has_not_been_observed' => '観察研究データが未登録です。',
    'id_not_found' => '削除されているか、存在しないIDです。',
    'invalid_sound' => '雑音が大きいため測定できませんでした。手動測定に切り替えてください。',
    'not_enough_recording_time' => '録音時間が短すぎます。再度録音してください。',
    'not_enough_pulses' => '十分なパルスが検出できませんでした。呼吸器に近づけて再度録音してください。',
    'required_for_principal_investigator' => '医師（施設内研究代表者の場合メールアドレスは必須です）',
    'organization_mismatch' => '組織情報が一致しません。',
    'unauthenticated' => '認証エラー',
    'user_token_required' => 'ユーザートークンは必須です。',
    'organization_mismatch' => '組織情報が一致しません。',
    'required_password' => 'パスワードは必須です。',
    'required_password_confirmation' => 'パスワード(確認用)は必須です。',
    'password_confirmed' => 'パスワードを確認用と一致させてください。',
    'excessive_number_of_registrations' => '登録数が超過しています。',
    'excessive_number_of_deletions' => '削除件数が超過しています。',
    'ventilator_value_exists_yet' => 'MicroVent機器の削除は、対象の機器が紐づく機器観察研究データをすべて削除してから実行してください。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    | カスタム検証言語
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    | ここでは、行に名前を付けるために "attribute.rule"という規則を使って属性のカスタム
    | 検証メッセージを指定することができます。 これにより、特定の属性ルールに対して特定の
    | カスタム言語行をすばやく指定できます。
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Values
    | カスタム検証値
    |--------------------------------------------------------------------------
    */

    'values' => [
        'org_authority_type' => [
            // 6 => 医師(施設内代表者)
            Models\User::ORG_PRINCIPAL_INVESTIGATOR_TYPE => __('messages.principal_investigator'),
        ]
    ],


    /*
      |--------------------------------------------------------------------------
      | Custom Validation Attributes
      | カスタム検証属性
      |--------------------------------------------------------------------------
      |
      | The following language lines are used to swap attribute place-holders
      | with something more reader friendly such as E-Mail Address instead
      | of "email". This simply helps us make messages a little cleaner.
      |
      | 次の言語行は、属性プレースホルダを「email」ではなく「E-Mail Address」などの
      | 読みやすいものと交換するために使用されます。
      |
    */

    'attributes' => [
        'authority' => '権限',
        'admin_authority_type' => "権限",
        'org_authority_type' => "権限",
        'height' => '身長',
        'weight' => '体重',
        'gender' => '性別',
        'name' => 'アカウント名',
        'password' => 'パスワード',
        'patient_code' => '患者番号',
        'airway_pressure' => '設定圧',
        'air_flow' => '空気流量',
        'o2_flow' => '酸素流量',
        'i' => '吸気',
        'e' => '呼気',
        'opt_out_flg' => 'オプトアウト',
        'age' => '年齢',
        'vent_disease_name' => 'Microventを使用した原因病名',
        'other_disease_name_1' => 'その他疾患名1',
        'other_disease_name_2' => 'その他疾患名2',
        'used_place' => '使用場所',
        'hospital' => '病院名',
        'national' => '国名',
        'discontinuation_at' => '使用中止日時',
        'outcome' => '使用中止時の転帰',
        'treatment' => '使用中止後の呼吸不全治療',
        'adverse_event_flg' => '機器に関する有害事象',
        'adverse_event_contents' => '機器に関する有害事象の内容',
        'status_use' => '使用状況',
        'status_use_other' => '使用状況（その他の場合）',
        'spo2' => '経皮酸素飽和度',
        'etco2' => '呼気終末二酸化炭素分圧',
        'start_using_at' => '使用開始日時',
        'email' => 'メールアドレス',
        'user_name' => 'ユーザー名',
        'ventilator_value_scan_interval' => '最終値決定時間',
        'vt_per_kg' => '理想体重1kgあたりの予測一回換気量',
        'organization_name'=>'組織名',
        'representative_name'=>'代表者名',
        'representative_email'=>'メールアドレス',
        'organization_code'=>'組織コード',
        'user_name'=>'組織管理者ユーザー名',
        'status'=>'ステータス',
        'edcid'=>'EDC施設ID',
        'patient_observation_status'=>'患者観察研究ステータス',
        'code'=>'組織コード',
        'password_confirmation'=>'パスワード(確認用)',
        'bug_name'=>'不具合名',
        'request_improvement'=>'改善要望',
    ],

];