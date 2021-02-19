<?php 

namespace App\Services\Support;

use App\Exceptions;

use DB;

class DBUtil {

    public static function Transaction($description, \Closure $transactional)
    {
        \Log::info('===START TRANSACTION ' . ($description ? $description : '') . '===');

        DB::beginTransaction();

        try {
            $transactional();

            // COMMIT
            DB::commit();

            \Log::debug('====SUCCESS TRANSACTION ' . ($description ? $description : '') . '===');
        } catch (\Exception $e) {
            $description = $description ? $description : '';

            $mes = "DBトランザクション処理@{$description} に失敗しました。";

            try {
                // ROLLBACK
                DB::rollback();

                $mes .= 'ロールバックに成功しています。';
            } catch (\Exception $e2) {
                $mes .= 'ロールバックに失敗しました。 Caused By ' . $e2->getMessage();
            }

            throw new Exceptions\DBUtilException($mes, $e);
        }
    }
}