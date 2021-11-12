<?php

namespace App\Jobs\Admin;

use App\Jobs\JobHandler;
use App\Services\Admin\VentilatorService;

class CreateVentilatorDataCsv extends JobHandler
{
    private $ids;

    /**
     * JobHandler::dispatchToHandleを通じて初期化される
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
     * @return void
     */
    protected function process()
    {
        $filename = self::guessFilename($this->queue);
        (new VentilatorService)->createVentilatorDataCsvByIds($filename, $this->ids);
    }

    public static function guessFilename(string $queue)
    {
        return $queue . '.csv';
    }
}
