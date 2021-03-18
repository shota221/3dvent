<?php

namespace App\Services;

use App\Repositories as Repos;
use App\Services\Support\Converter;
use App\Http\Response as Response;
use App\Services\Support;
use App\Exceptions;
use App\Exceptions\InvalidException;
use App\Models\VentilatorValue;
use App\Models\Format;
use App\Repositories\VentilatorValueRepository;
use App\Services\Support\DateUtil;

class VentilatorValueBatchService
{

    public function test()
    {
        echo 'test';
    }

    public function updateFixedFlg()
    {
        $ago = config('system.fixed_flg_interval');

        $query = Repos\VentilatorValueRepository::queryToUpdateFixedFlg(DateUtil::hourAgo(DateUtil::now(), $ago));
        //なぜか$query->update(['fixed_flg'=>1])がエラーとなるので一旦こっちで
        Support\DBUtil::Transaction(
            'fixed_flgを立てる',
            function () use ($query) {
                foreach ($query->get() as $entity) {
                    $entity->fixed_flg = 1;
                    $entity->save();
                }
            }
        );
    }
}
