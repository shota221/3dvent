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
        $ago = config('system.fixed_flg_interval');

        $sql = 
        'UPDATE ventilator_values 
          SET fixed_flg = '.VentilatorValue::FIX.' 
          WHERE id IN (SELECT max_id FROM (SELECT MAX(id) as max_id FROM ventilator_values GROUP BY ventilator_id) AS TEMP) AND created_at <= "'.DateUtil::hourAgo(DateUtil::now(), $ago) .'"';

        Support\DBUtil::Transaction(
            'fixed_flgを立てる',
            function () use ($sql) {
                \DB::update($sql);
            }
        );

    }
}
