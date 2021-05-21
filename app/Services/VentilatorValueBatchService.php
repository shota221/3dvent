<?php

namespace App\Services;

use App\Repositories as Repos;
use App\Exceptions;
use App\Models\VentilatorValue;
use App\Services\Support\DateUtil;
use App\Services\Support\DBUtil;

class VentilatorValueBatchService
{
    public function updateFixedFlg()
    {
        $interval = config('system.fixed_flg_interval');

        $chunk_size = 5000;

        $now = DateUtil::now();

        $fix_ids = [];

        $scanned_ids = [];

        //連想配列を用いて同ventilator_idを持つ前の値とregistered_atを比較する。
        $ventilator_id_to_current_value_map = [];

        $query = Repos\VentilatorValueRepository::queryByScannedAtIsNullOrderByRegisteredAtASC();

        $query->chunk(
            $chunk_size,
            function ($ventilator_values) use ($interval, &$fix_ids, &$scanned_ids, &$ventilator_id_to_current_value_map) {

                foreach ($ventilator_values as $ventilator_value) {

                    $ventilator_id = $ventilator_value->ventilator_id;

                    if (isset($ventilator_id_to_current_value_map[$ventilator_id])) {

                        $current_value = $ventilator_id_to_current_value_map[$ventilator_id];

                        $registered_at_next = DateUtil::datetimeStrToCarbon($ventilator_value->registered_at);

                        $registered_at = DateUtil::datetimeStrToCarbon($current_value->registered_at);

                        if ($registered_at->diffInHours($registered_at_next) >= $interval) {
                            $fix_ids[] = $current_value->id;
                        }

                        $scanned_ids[] = $current_value->id;
                    }

                    $ventilator_id_to_current_value_map[$ventilator_id] = $ventilator_value;
                }
            }
        );

        //最新データに対してはfixed_flgが立つものに対してのみscanned_atを記録する。
        foreach ($ventilator_id_to_current_value_map as $ventilator_value) {
            if (DateUtil::datetimeStrToCarbon($ventilator_value->registered_at)->diffInHours($now) >= $interval) {
                $fix_ids[] = $ventilator_value->id;
                $scanned_ids[] = $ventilator_value->id;
            }
        }

        DBUtil::Transaction(
            'fixed_flgを建ててfixed_at,scanned_atを記録',
            function () use ($fix_ids, $scanned_ids, $now) {
                //fixed_flg・fixed_atの更新
                Repos\VentilatorValueRepository::updateFixedFlgAndFixedAt($fix_ids, $now);

                //scanned_atの更新
                Repos\VentilatorValueRepository::updateScannedAt($scanned_ids, $now);
            }
        );
    }
}
