<?php

namespace App\Jobs\Admin;

use App\Jobs\JobHandler;
use App\Services\Admin\VentilatorService;

class ImportVentilatorData extends JobHandler
{
    private $organization_id;
    private $file;

    /**
     * JobHandler::dispatchToHandleを通じて初期化される
     *
     * @return void
     */
    public function __construct(int $organization_id, $file)
    {
        $this->organization_id = $organization_id;
        $this->file = $file;
    }

    /**
     * @override
     *
     * @param string $filename
     * @param array $ids
     * @return void
     */
    protected function process()
    {
        (new VentilatorService)->importVentilatorData($this->organization_id, $this->file);
    }
}
