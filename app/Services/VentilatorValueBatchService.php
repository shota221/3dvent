<?php

namespace App\Services;

use App\Repositories as Repos;
use App\Services\Support\Converter;
use App\Http\Response as Response;
use App\Services\Support;
use App\Exceptions;
use App\Exceptions\InvalidException;
use App\Models\VentilatorValue;
use App\Models\Format;
use App\Repositories\VentilatorValueRepository;
use App\Services\Support\DateUtil;

class VentilatorValueBatchService
{

    public function test()
    {
        echo 'test';
    }

    public function updateFixedFlg()
    {
        $interval = config('system.fixed_flg_interval');

        /**
         * TODO バッチ処理一本型
         * 前回バッチが処理した最後のIDを保持しておいて、
         * そのID以降の全データをなめ、一時間インターバルが空いているレコードにFIXを立てるという実装
         * 
         * 現実装だと、7:10登録 8:00バッチ処理 8:20登録といったケースで7:10のレコードにfixがつかないため。
         */
        Support\DBUtil::Transaction(
            'fixed_flgを立てる',
            function () use ($interval) {
                Repos\VentilatorValueRepository::updateFixedFlg(DateUtil::toDatetimeStr(DateUtil::now()) ,$interval);
            }
        );
    }
}
