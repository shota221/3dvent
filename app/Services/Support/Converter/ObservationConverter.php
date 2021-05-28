<?php

namespace App\Services\Support\Converter;

use App\Http\Forms\Api as Form;
use App\Http\Response as Response;
use App\Models;

class ObservationConverter
{
  
    public static function convertToObservationCount(int $ventilator_observed_count, int $patient_observed_count, int $ventilator_bug_count)
    {
        $res = new Response\Api\ObservationCount;

        $res->ventilator_observed_count = $ventilator_observed_count;

        $res->patient_observed_count = $patient_observed_count;

        $res->ventilator_bug_count = $ventilator_bug_count;
 
        return $res;
    }

    public static function convertToPatientObservedValueListElm($patient_observed_value)
    {
        $res = new Response\Api\PatientObservedValueListElm;

        $res->operation = $patient_observed_value->operation;
   
        $res->patient_id = $patient_observed_value->patient_id;
    
        $res->organization_id = $patient_observed_value->organization_id;
        
        $res->optout = $patient_observed_value->opt_out_flg;

        if (!$patient_observed_value->opt_out_flg) {

        
            $res->organization_name = $patient_observed_value->organization_name;
        
            $res->patient_code = $patient_observed_value->patient_code;
        
            $res->age = strval($patient_observed_value->age);
        
            $res->vent_disease_name = $patient_observed_value->vent_disease_name;
        
            $res->other_disease_name_1 = $patient_observed_value->other_disease_name_1;
        
            $res->other_disease_name_2 = $patient_observed_value->other_disease_name_2;
        
            $res->used_place = $patient_observed_value->used_place;
        
            $res->hospital = $patient_observed_value->hospital;
        
            $res->national = $patient_observed_value->national;
        
            $res->discontinuation_at = $patient_observed_value->discontinuation_at;
        
            $res->outcome = $patient_observed_value->outcome;
        
            $res->treatment = $patient_observed_value->treatment;
        
            $res->adverse_event_flg = $patient_observed_value->adverse_event_flg;
        
            $res->adverse_event_contents = $patient_observed_value->adverse_event_contents;
        
            $res->registered_at = $patient_observed_value->registered_at;
        }
    

        return $res;
    }

    public static function convertToVentilatorObservedValueListElm($ventilator_observed_value)
    {
        $res = new Response\Api\VentilatorObservedValueListElm;

        $res->operation = $ventilator_observed_value->operation;

        $res->patient_id = $ventilator_observed_value->patient_id;
        
        $res->organization_id = $ventilator_observed_value->organization_id;
    
        $res->ventilator_id = $ventilator_observed_value->ventilator_id;
        
        $res->ventilator_value_id = $ventilator_observed_value->ventilator_value_id;
    
        $res->serial_number = $ventilator_observed_value->serial_number;
    
        $res->user_name = $ventilator_observed_value->user_name;
        
        $res->city = $ventilator_observed_value->city;
        
        $res->gender = $ventilator_observed_value->gender;
        
        $res->height = $ventilator_observed_value->height;
        
        $res->weight = $ventilator_observed_value->weight;
        
        $res->ideal_weight = $ventilator_observed_value->ideal_weight;
        
        $res->status_use = $ventilator_observed_value->status_use;
        
        $res->status_use_other = $ventilator_observed_value->status_use_other;
        
        $res->vt_per_kg = $ventilator_observed_value->vt_per_kg;
        
        $res->predicted_vt = $ventilator_observed_value->predicted_vt;
        
        $res->airway_pressure = $ventilator_observed_value->airway_pressure;
        
        $res->air_flow = $ventilator_observed_value->air_flow;
        
        $res->o2_flow = $ventilator_observed_value->o2_flow;
        
        $res->total_flow = $ventilator_observed_value->total_flow;
        
        $res->fio2 = $ventilator_observed_value->fio2;
       
        $res->inspiratory_time = $ventilator_observed_value->inspiratory_time;
       
        $res->expiratory_time = $ventilator_observed_value->expiratory_time;
       
        $res->rr = $ventilator_observed_value->rr;
       
        $res->estimated_vt = $ventilator_observed_value->estimated_vt;
       
        $res->estimated_mv = $ventilator_observed_value->estimated_mv;
       
        $res->spo2 = $ventilator_observed_value->spo2;
       
        $res->etco2 = $ventilator_observed_value->etco2;
       
        $res->pao2 = $ventilator_observed_value->pao2;
       
        $res->paco2 = $ventilator_observed_value->paco2;
       
        $res->qr_read_at = $ventilator_observed_value->qr_read_at;
    
        $res->start_using_at = $ventilator_observed_value->start_using_at;
       
        $res->registered_at = $ventilator_observed_value->registered_at;

        return $res;
    }

    public static function convertToVentilatorBugListElm($ventilator_bug)
    {
        $res = new Response\Api\VentilatorBugListElm;

        $res->organization_id = $ventilator_bug->organization_id;

        $res->ventilator_id = $ventilator_bug->ventilator_id;
    
        $res->bug_name = $ventilator_bug->bug_name;
        
        $res->request_improvement = $ventilator_bug->request_improvement;
    
        $res->registered_at = $ventilator_bug->registered_at;
 
        return $res;
    }
}