<?php

namespace App\Jobs\Admin;

use App\Jobs\JobHandler;
use App\Services\Admin\VentilatorService;

class ImportVentilatorData extends JobHandler
{
    private $organization_id;
    private $valid_rows;
    private $registered_user_id;

    /**
     * JobHandler::dispatchToHandleを通じて初期化される
     *
     * @return void
     */
    public function __construct(int $organization_id, array $valid_rows, int $registered_user_id)
    {
        $this->organization_id = $organization_id;
        $this->valid_rows = $valid_rows;
        $this->registered_user_id = $registered_user_id;
    }

    /**
     * @override
     *
     * @return void
     */
    protected function process()
    {
        (new VentilatorService)->importVentilatorData($this->organization_id, $this->valid_rows, $this->registered_user_id);
    }
}
