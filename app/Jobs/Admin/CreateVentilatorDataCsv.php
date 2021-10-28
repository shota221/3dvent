<?php

namespace App\Jobs\Admin;

use App\Jobs\CreateSearchDataCsv;
use App\Services\Admin\VentilatorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateVentilatorDataCsv extends CreateSearchDataCsv
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $search_values)
    {
        $this->search_values = $search_values;
    }

    /**
     *  非同期ディスパッチ（ジョブをキューに登録）、処理
     * @param string $queue //キュー名
     * @param array $ids
     * @return void
     */
    public static function dispatchToHandle(string $queue, array $ids)
    {
        parent::dispatchToHandle($queue, compact('ids'));
    }

    /**
     * @override
     *
     * @param string $queue
     * @param array $search_values
     * @return void
     */
    protected function create(string $queue, array $search_values)
    {
        (new VentilatorService)->createVentilatorCsvByIds($queue, $search_values['ids']);
    }
}
