<?php

namespace App\Services\Support\Converter;

use App\Http\Forms\Api as Form;
use App\Http\Response as Response;
use App\Models;

class BugReportConverter
{
  public static function convertToBugReportRegistrationResult($ventilator_id)
  {
    $res = new Response\Api\BugReportResult;

    $res->ventilator_id = $ventilator_id;

    return $res;
  }

  public static function convertToEntity(
    $ventilator_id,
    $bug_name,
    $request_improvement,
    $registered_at,
    $appkey_id,
    $bug_registered_user_id = null
  ) {
    $entity = new Models\VentilatorBug;

    $entity->ventilator_id = $ventilator_id;

    $entity->bug_name = $bug_name;

    $entity->request_improvement = $request_improvement;

    $entity->registered_at = $registered_at;

    $entity->appkey_id = $appkey_id;

    $entity->bug_registered_user_id = $bug_registered_user_id;

    return $entity;
  }
}
