<?php

namespace App\Services;

use App\Repositories as Repos;
use App\Exceptions;
use App\Models\VentilatorValue;
use App\Services\Support\DateUtil;
use App\Services\Support\DBUtil;

class VentilatorValueBatchService
{
    public function updateFixedFlg($chunk_size)
    {
        $interval = config('system.fixed_flg_interval');

        $now = DateUtil::now();

        $registered_at_to = DateUtil::hourAgo($now, $interval);

        //機器観察研究データが新しく挿入されたventilator_idのリストを取得
        $ventilator_ids_to_scan = Repos\VentilatorValueRepository::listOfVentilatorIdByScannedAtIsNullAndRegisteredAtTo($registered_at_to);

        //取得したvenilator_idごとにventilator_valueを取得し、レコード間のregistered_at差分を見て、$intervalだけ経っていれば$ids_to_fixにid追加
        $ids_to_fix = [];
        $ids_scanned = [];

        foreach ($ventilator_ids_to_scan as $ventilator_id) {
            //ventilator_idごとのレコード群を降順取得
            $ventilator_values_to_scan = Repos\VentilatorValueRepository::findByVenitilatorIdAndScannedAtIsNull($ventilator_id)->all();

            //最新のレコードが現在から$interval経っているかどうか
            $registered_at = DateUtil::datetimeStrToCarbon($ventilator_values_to_scan[0]->registered_at);

            if ($registered_at->diffInHours($now) >= $interval) {
                $ids_to_fix[] = $ventilator_values_to_scan[0]->id;
                $ids_scanned[] = $ventilator_values_to_scan[0]->id;
            }

            if (count($ventilator_values_to_scan) === 1) continue;

            //それ以前のレコード間の$registered_at差分が$interval以上かどうか
            for ($i = 1; $i < count($ventilator_values_to_scan); $i++) {

                $registered_at = DateUtil::datetimeStrToCarbon($ventilator_values_to_scan[$i]->registered_at);

                $registered_at_next = DateUtil::datetimeStrToCarbon($ventilator_values_to_scan[$i - 1]->registered_at);

                if ($registered_at->diffInHours($registered_at_next) >= $interval) {
                    $ids_to_fix[] = $ventilator_values_to_scan[$i]->id;
                }
                $ids_scanned[] = $ventilator_values_to_scan[$i]->id;
            }
        }

        DBUtil::Transaction(
            'fixed_flgを建ててfixed_at,scanned_atを記録',
            function () use ($ids_to_fix, $ids_scanned, $now) {
                //fixed_flg・fixed_atの更新
                Repos\VentilatorValueRepository::updateBulkFixedFlgAndFixedAt($ids_to_fix, $now);

                //scanned_atの更新
                Repos\VentilatorValueRepository::updateBulkScannedAt($ids_scanned, $now);
            }
        );
    }
}
