<?php 

namespace App\Http\Response;

/**
 * Api Success Json response
 */
class SuccessJsonResult extends JsonResult
{
    public function jsonSerialize()
    {
        return ['result' => parent::jsonSerialize()];
    }
}