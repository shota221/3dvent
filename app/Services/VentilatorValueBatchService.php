<?php

namespace App\Services;

use App\Repositories as Repos;
use App\Exceptions;
use App\Models\VentilatorValue;
use App\Services\Support\DateUtil;

class VentilatorValueBatchService
{
    public function updateFixedFlg($chunk_size)
    {
        $interval = config('system.fixed_flg_interval');

        $now = DateUtil::now();

        $registered_at_to = DateUtil::hourAgo($now, $interval);

        $search_query_to_scan = Repos\VentilatorValueRepository::queryByScannedAtIsNullAndRegisteredAtTo($registered_at_to);

        $search_query_to_scan
            ->chunk(
                $chunk_size,
                function ($ventilater_values) use ($now, $interval){
                    foreach ($ventilater_values as $ventilator_value) {
                        $ventilator_id = $ventilator_value->ventilator_id;

                        $registered_at = DateUtil::datetimeStrToCarbon($ventilator_value->registered_at);

                        $from = DateUtil::toDatetimeStr($registered_at);

                        $to = DateUtil::toDatetimeStr(DateUtil::hourLater($registered_at, $interval));

                        $exists = Repos\VentilatorValueRepository::existsByVentilatorIdAndRegisteredAtFromTo($ventilator_id, $from, $to);

                        if (!$exists) {
                            $ventilator_value->fixed_flg = VentilatorValue::FIX;
                            $ventilator_value->fixed_at = $now;
                        }

                        $ventilator_value->ventilator_value_scanned_at = $now;

                        $ventilator_value->save();
                    }
                }
            );
    }
}
