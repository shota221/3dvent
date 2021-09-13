<?php

namespace App\Services\Support;

/**
 * wavファイル用DTO
 */
class Gs1Data
{
    const AI_LENGTH = 2,//アプリケーション識別子(01,17,10,21等)長
        GTIN_LENGTH = 14,
        EXPIRARION_DATE_LENGTH = 6,
        LOT_NUMBER_LENGTH = 6;//本来は可変長であるが、ニュートンからの情報を受け一旦6桁で固定。TODO:実際のバーコードが発行され次第法則適用

    public $gtin; //GTIN(01):固定長

    public $expiration_date; //有効期限(17):固定長

    public $lot_number; //ロット番号(10):可変長

    public $serial_number; //シリアル番号(21):可変長
}
