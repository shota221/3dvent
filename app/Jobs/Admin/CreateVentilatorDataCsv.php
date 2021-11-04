<?php

namespace App\Jobs\Admin;

use App\Jobs\CreateSearchDataCsv;
use App\Jobs\JobHandler;
use App\Services\Admin\VentilatorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateVentilatorDataCsv extends JobHandler
{
    private $ids;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    /**
     * @override
     *
     * @param string $queue
     * @param array $ids
     * @return void
     */
    protected function process()
    {
        (new VentilatorService)->createVentilatorCsvByIds($this->queue, $this->ids);
    }
}
