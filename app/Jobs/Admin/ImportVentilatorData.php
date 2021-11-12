<?php

namespace App\Jobs\Admin;

use App\Jobs\JobHandler;
use App\Services\Admin\VentilatorService;
use App\Exceptions;

class ImportVentilatorData extends JobHandler
{
    private $organization_id;
    private $file_path;
    private $registered_user_id;

    /**
     * JobHandler::dispatchToHandleを通じて初期化される
     *
     * @return void
     */
    public function __construct(int $organization_id, string $file_path, int $registered_user_id)
    {
        $this->organization_id = $organization_id;
        $this->file_path = $file_path;
        $this->registered_user_id = $registered_user_id;
    }

    /**
     * @override
     *
     * @return void
     */
    protected function process()
    {
        (new VentilatorService)->importVentilatorData($this->organization_id, $this->file_path, $this->registered_user_id);
    }
}
