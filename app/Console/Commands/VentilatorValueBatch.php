<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Services\VentilatorValueBatchService;

use App\Exceptions\BatchErrorException;


class VentilatorValueBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set_fixed_flg';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'setting...';

    private $service;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->service = new VentilatorValueBatchService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->service->updateFixedFlg();
        } catch (\Exception $e) {
            throw new BatchErrorException('VentilatorValueBatch 処理に失敗しました。', $e);
        }

        \Log::info('VentilatorValueBatch 完了しました。');
    }
}
