<?php

namespace App\Services\Support\Converter;

use App\Http\Forms\Api as Form;
use App\Http\Response as Response;
use App\Models;

class ObservationConverter
{
  
    //TODO 以下補完作業
    public static function convertToObservationCount()
    {
        return <<<EOF
        {
            "result":{
                "ventilator_observed_count":3,
                "patient_observed_count":6
            }
        }
        EOF;
    }

    public static function convertToPatientList()
    {
        return <<<EOF
        {
            "result":[
                {
                    "operation":"create",
                    "patient_id":1,
                    "optout":0,
                    "organization_name":"●●施設",
                    "patient_code":"nb00001",
                    "age":"50",
                    "vent_disease_name":"",
                    "other_disease_name_1":"",
                    "other_disease_name_2":"",
                    "used_place":1,
                    "hospital":"",
                    "national":"",
                    "discontinuation_at":"",
                    "adverse_event_flg":0,
                    "adverse_event_contents":"",
                    "registered_at":"2021/05/11 13:50:10"
                },
                {
                    "operation":"delete",
                    "patient_id":1,
                    "optout":0,
                    "organization_name":"●●施設",
                    "patient_code":"nb00001",
                    "age":"50",
                    "vent_disease_name":"",
                    "other_disease_name_1":"",
                    "other_disease_name_2":"",
                    "used_place":1,
                    "hospital":"",
                    "national":"",
                    "discontinuation_at":"",
                    "adverse_event_flg":0,
                    "adverse_event_contents":"",
                    "registered_at":"2021/05/11 13:50:10"
                },
                {
                    "operation":"create",
                    "patient_id":1,
                    "optout":0,
                    "organization_name":"●●施設",
                    "patient_code":"nb00001",
                    "age":"50",
                    "vent_disease_name":"肺炎",
                    "other_disease_name_1":"糖尿病",
                    "other_disease_name_2":"高血圧症",
                    "used_place":1,
                    "hospital":"●●病院",
                    "national":"日本",
                    "discontinuation_at":"2021/05/13 14:50",
                    "outcome":1,
                    "treatment":2,
                    "adverse_event_flg":0,
                    "adverse_event_contents":"",
                    "registered_at":"2021/05/11 13:50:10"
                },
                {
                    "operation":"create",
                    "patient_id":2,
                    "optout":0,
                    "organization_name":"●●施設",
                    "patient_code":"nb00002",
                    "age":"50",
                    "vent_disease_name":"",
                    "other_disease_name_1":"",
                    "other_disease_name_2":"",
                    "used_place":1,
                    "hospital":"",
                    "national":"",
                    "discontinuation_at":"",
                    "adverse_event_flg":0,
                    "adverse_event_contents":"",
                    "registered_at":"2021/05/11 15:50:10"
                },
                {
                    "operation":"delete",
                    "patient_id":2,
                    "optout":0,
                    "organization_name":"●●施設",
                    "patient_code":"nb00002",
                    "age":"50",
                    "vent_disease_name":"",
                    "other_disease_name_1":"",
                    "other_disease_name_2":"",
                    "used_place":1,
                    "hospital":"",
                    "national":"",
                    "discontinuation_at":"",
                    "adverse_event_flg":0,
                    "adverse_event_contents":"",
                    "registered_at":"2021/05/11 15:50:10"
                },
                {
                    "operation":"create",
                    "patient_id":2,
                    "optout":1
                }
            ]
        }
        EOF;
    }

    public static function convertToVentilatorList()
    {
        return <<<EOF
        {
            "result":[
                {
                    "operation":"create",
                    "organization_id":1,
                    "ventilator_id":5,
                    "patient_id":1,
                    "ventilator_value_id":1,
                    "serial_number":"13216",
                    "qr_read_at":"2021/05/11 12:50:30",
                    "start_using_at":"2021/05/11 12:50:30",
                    "city":"千代田区, 東京都, 日本",
                    "user_name":"satou",
                    "gender":1,
                    "height":"170.5",
                    "weight":"60.5",
                    "ideal_weight":"63.2",
                    "status_use_other":"",
                    "vt_per_kg":"6",
                    "predicted_vt":"352.4",
                    "airway_pressure":"20",
                    "air_flow":"8",
                    "o2_flow":"4",
                    "total_flow":"12",
                    "fio2":"85.2",
                    "inspiratory_time":"1.340",
                    "expiratory_time":"0.657",
                    "rr":"30.04",
                    "estimated_vt":"268.1",
                    "estimated_mv":"8.05",
                    "spo2":"",
                    "etco2":"",
                    "pao2":"",
                    "paco2":"4.5",
                    "registered_at":"2021/05/11 13:50:10",
                    "ventilator_bugs":[
                        {
                            "bug_name":"",
                            "request_improvement":""
                        }
                    ]
                },
                {
                    "operation":"delete",
                    "organization_id":1,
                    "ventilator_id":5,
                    "patient_id":1,
                    "ventilator_value_id":1,
                    "serial_number":"13216",
                    "qr_read_at":"2021/05/11 12:50:30",
                    "start_using_at":"2021/05/11 12:50:30",
                    "city":"千代田区, 東京都, 日本",
                    "user_name":"satou",
                    "gender":1,
                    "height":"170.5",
                    "weight":"60.5",
                    "ideal_weight":"63.2",
                    "status_use_other":"",
                    "vt_per_kg":"6",
                    "predicted_vt":"352.4",
                    "airway_pressure":"20",
                    "air_flow":"8",
                    "o2_flow":"4",
                    "total_flow":"12",
                    "fio2":"85.2",
                    "inspiratory_time":"1.340",
                    "expiratory_time":"0.657",
                    "rr":"30.04",
                    "estimated_vt":"268.1",
                    "estimated_mv":"8.05",
                    "spo2":"",
                    "etco2":"",
                    "pao2":"",
                    "paco2":"4.5",
                    "registered_at":"2021/05/11 13:50:10",
                    "ventilator_bugs":[
                        {
                            "bug_name":"",
                            "request_improvement":""
                        }
                    ]
                },
                {
                    "operation":"create",
                    "organization_id":1,
                    "ventilator_id":5,
                    "patient_id":1,
                    "ventilator_value_id":1,
                    "serial_number":"13216",
                    "qr_read_at":"2021/05/11 12:50:30",
                    "start_using_at":"2021/05/11 12:50:30",
                    "city":"千代田区, 東京都, 日本",
                    "user_name":"satou",
                    "gender":1,
                    "height":"170.5",
                    "weight":"60.5",
                    "ideal_weight":"63.2",
                    "status_use":3,
                    "status_use_other":"",
                    "vt_per_kg":"6",
                    "predicted_vt":"352.4",
                    "airway_pressure":"20",
                    "air_flow":"8",
                    "o2_flow":"4",
                    "total_flow":"12",
                    "fio2":"85.2",
                    "inspiratory_time":"1.340",
                    "expiratory_time":"0.657",
                    "rr":"30.04",
                    "estimated_vt":"268.1",
                    "estimated_mv":"8.05",
                    "spo2":"92.5",
                    "etco2":"12.5",
                    "pao2":"5.5",
                    "paco2":"4.5",
                    "registered_at":"2021/05/11 13:50:10",
                    "ventilator_bugs":[
                        {
                            "bug_name":"切替音が小さい",
                            "request_improvement":"呼気、吸気切替音をもう少し大きくしてほしい。"
                        }
                    ]
                }
            ]
        }
        EOF;
    }
}
