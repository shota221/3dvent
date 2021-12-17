<?php

namespace App\Services\Support;

class Gs1Util
{
    public static function extractGs1Data(string $gs1_code)
    {
        $gs1_data = new Gs1Data;

        while (!empty($gs1_code)) {
            $ai = substr($gs1_code, 0, Gs1Data::AI_LENGTH);
            switch ($ai) {
                case '01': //GTIN
                    $gs1_data->gtin = substr($gs1_code, Gs1Data::AI_LENGTH, Gs1Data::GTIN_LENGTH);
                    $gs1_code = substr_replace($gs1_code, '', 0, Gs1Data::AI_LENGTH + Gs1Data::GTIN_LENGTH);
                    break;
                case '17': //有効期限
                    $expiration_date_str = substr($gs1_code, Gs1Data::AI_LENGTH, Gs1Data::EXPIRARION_DATE_LENGTH);
                    $gs1_data->expiration_date = DateUtil::parseToDate($expiration_date_str, DateUtil::DATE_FORMAR_CHAR_SHORT);
                    $gs1_code = substr_replace($gs1_code, '', 0, Gs1Data::AI_LENGTH + Gs1Data::EXPIRARION_DATE_LENGTH);
                    break;
                case '10': //ロット番号
                    $gs1_data->lot_number = substr($gs1_code, Gs1Data::AI_LENGTH, Gs1Data::LOT_NUMBER_LENGTH);
                    $gs1_code = substr_replace($gs1_code, '', 0, Gs1Data::AI_LENGTH + Gs1Data::LOT_NUMBER_LENGTH);
                    break;
                case '21': //シリアル番号：可変長。最後に記載されると仮定。TODO:実際のバーコードが発行され次第法則適用。
                    $gs1_data->serial_number = substr($gs1_code, Gs1Data::AI_LENGTH);
                    $gs1_code = '';
                    break;
                default: //他の情報も乗る可能性有り。TODO:実際のバーコードが発行され次第法則適用。
                    break 2;
            }
        }

        return $gs1_data;
    }

    /**
     * gs1コード読み取り時に含まれる余計な文字列を除去
     *
     * @param string $raw_gs1_code
     * @return string $sanitized_gs1_code
     */
    public static function sanitizeGs1Code(string $raw_gs1_code)
    {
        //gs1コード宣言"]C1"があれば除去
        $raw_gs1_code = str_replace(']C1', '', $raw_gs1_code);
        //その他英数字以外の文字があれば除去
        $sanitized_gs1_code = preg_replace('/[^0-9a-zA-Z]/', '', $raw_gs1_code);
        return $sanitized_gs1_code;
    }
}
