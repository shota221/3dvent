<?php 

namespace App\Services\Support\Converter;

use App\Http\Forms\Api as Form;
use App\Http\Response as Response;
use App\Models;
use App\Services\Support\Converter;
use App\Services\Support\DateUtil;
use Illuminate\Pagination\LengthAwarePaginator;

class PatientValueConverter
{
    public static function convertToPaginatedPatientValueData(
        \Illuminate\Database\Eloquent\Collection $entities,
        int $total_count,
        int $item_per_page,
        string $path)
    {
        $patient_values = array_map(
            function($entity) {
                return self::convertToPatientValueData($entity);
            }
            , $entities->all()
        );

        $paginator = new LengthAwarePaginator(
            $patient_values,
            $total_count,
            $item_per_page,
            null,
            ['path' => $path]
        );

        return $paginator;
    }

    public static function convertToPatientValueData(Models\PatientValue $entity)
    {
        $data = new Response\Admin\PatientValueData;

        $data->id = $entity->id;
        $data->patient_code = $entity->patient_code;
        $data->organization_name = $entity->organization_name;
        $data->registered_user_name = $entity->registered_user_name;
        $data->registered_at = $entity->registered_at;
        $data->updated_at = DateUtil::toDatetimeStr($entity->created_at);
        $data->opt_out_flg = $entity->opt_out_flg;
        $data->age = $entity->age;
        $data->vent_disease_name = $entity->vent_disease_name;
        $data->other_disease_name_1 = $entity->other_disease_name_1;
        $data->other_disease_name_2 = $entity->other_disease_name_2;
        $data->used_place = $entity->used_place;
        $data->used_place_name = Converter\SelectedNameConverter::convertToUsedPlaceName($entity->used_place);
        $data->hospital = $entity->hospital;
        $data->national = $entity->national;
        $data->discontinuation_at = $entity->discontinuation_at;
        $data->outcome = $entity->outcome;
        $data->outcome_name = Converter\SelectedNameConverter::convertToOutcomeName($entity->outcome);
        $data->treatment = $entity->treatment;
        $data->treatment_name = Converter\SelectedNameConverter::convertToTreatmentName($entity->treatment);
        $data->adverse_event_flg = $entity->adverse_event_flg;
        $data->adverse_event_contents = $entity->adverse_event_contents;

        return $data;
    }

    public static function convertToPatientValueEditData(Models\PatientValue $entity)
    {
        $data = new Response\Admin\PatientValueEditData;

        $data->id = $entity->id;
        $data->patient_code = $entity->patient_code;
        $data->organization_name = $entity->organization_name;
        $data->opt_out_flg = $entity->opt_out_flg;
        $data->age = $entity->age;
        $data->vent_disease_name = $entity->vent_disease_name;
        $data->other_disease_name_1 = $entity->other_disease_name_1;
        $data->other_disease_name_2 = $entity->other_disease_name_2;
        $data->used_place = $entity->used_place;
        $data->hospital = $entity->hospital;
        $data->national = $entity->national;
        $data->discontinuation_at = $entity->discontinuation_at;
        $data->outcome = $entity->outcome;
        $data->treatment = $entity->treatment;
        $data->adverse_event_flg = $entity->adverse_event_flg;
        $data->adverse_event_contents = $entity->adverse_event_contents;

        return $data;
    }
    
    public static function convertToOrganizationSearchListData(\Illuminate\Database\Eloquent\Collection $entities)
    {
        $organizations = array_map(
            function($entity) {
                return self::convertToOrganizationData($entity);
            }
            ,$entities->all()
        );

        return $organizations;
    }
    
    private static function convertToOrganizationData(Models\Organization $entity)
    {
        $data = new Response\Admin\OrganizationData();

        $data->id = $entity->id;
        $data->name = $entity->name;

        return $data;
    }

    public static function convertToRegisteredUserSearchListData(\Illuminate\Database\Eloquent\Collection $entities)
    {
        $registered_users = array_map(
            function($entity) {
                return self::convertToRegisteredUserData($entity);
            }
            ,$entities->all()
        );

        return $registered_users;
    }
    
    private static function convertToRegisteredUserData(Models\User $entity)
    {
        $data = new Response\Admin\RegisteredUserData();

        $data->id = $entity->id;
        $data->name = $entity->name;

        return $data;
    }
}