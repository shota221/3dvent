<?php 

namespace App\Services\Support\Converter;

use App\Http\Response as Response;

class QueueConverter
{
    public static function convertToQueueStatusResult($queue, $is_finished = false, $has_error = false) 
    {
        $res = new Response\QueueStatusResult;

        $res->queue = $queue;

        $res->is_finished = $is_finished;

        $res->has_error = $has_error;
        
        return $res;
    }
}