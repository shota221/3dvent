<?php 

namespace App\Services\Support\Converter;

use App\Http\Response as Response;
use App\Models\Appkey;
use App\Http\Forms\Api as Form;


class AppkeyConverter
{
    public static function convertToEntity(Form\AppkeyCreateForm $form)
    {
        $entity = new Appkey;
        
        $entity->idfv = $form->idfv;

        $entity->appkey = $form->appkey;

        return $entity;
    }

    public static function convertToAppkeyResult($entity) 
    {
        $res = new Response\Api\AppkeyResult;

        $res->appkey = $entity->appkey;

        return $res;
    }
}