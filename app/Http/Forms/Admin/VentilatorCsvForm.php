<?php

namespace App\Http\Forms\Admin;

use App\Http\Forms\ValidationRule as Rule;
use App\Http\Forms\BaseForm;
use App\Services\Support;
use App\Exceptions;

class VentilatorCsvForm extends BaseForm
{
    const REQUIRED_ON_PATIENT = 'required_if:patient_exists,1',
        REQUIRED_ON_PATIENT_VALUE =  'required_if:patient_value_exists,1',
        REQUIRED_ON_VENTILATOR_VALUE =  'required_if:ventilator_value_exists,1';

    public $org_ventilator_id;
    public $gs1_code;
    public $serial_number;
    public $city;
    public $qr_read_at;
    public $expiration_date;
    public $start_using_at;
    public $patient_exists;
    public $org_patient_id;
    public $patient_code;
    public $patient_height;
    public $patient_weight;
    public $patient_gender;
    public $patient_value_exists;
    public $patient_value_registered_at;
    public $age;
    public $vent_disease_name;
    public $other_disease_name_1;
    public $other_disease_name_2;
    public $used_place;
    public $hospital;
    public $national;
    public $discontinuation_at;
    public $outcome;
    public $treatment;
    public $adverse_event_flg;
    public $adverse_event_contents;
    public $opt_out_flg;
    public $ventilator_value_exists;
    public $appkey_id;
    public $ventilator_value_registered_at;
    public $height;
    public $weight;
    public $gender;
    public $ideal_weight;
    public $airway_pressure;
    public $total_flow;
    public $air_flow;
    public $o2_flow;
    public $rr;
    public $expiratory_time;
    public $inspiratory_time;
    public $vt_per_kg;
    public $predicted_vt;
    public $estimated_vt;
    public $estimated_mv;
    public $estimated_peep;
    public $fio2;
    public $status_use;
    public $status_use_other;
    public $spo2;
    public $etco2;
    public $pao2;
    public $paco2;
    public $fixed_flg;
    public $fixed_at;
    public $confirmed_flg;
    public $confirmed_at;

    protected function validationRule()
    {
        return [
            'ventilator_id' => 'required|' . Rule::VALUE_POSITIVE_NON_ZERO,
            'gs1_code' => 'required|' . Rule::VALUE_STRING,
            'serial_number' => 'required|' . Rule::VALUE_STRING,
            'city' => 'nullable|' . Rule::VALUE_STRING,
            'qr_read_at' => 'required|date',
            'expiration_date' => 'nullable|date',
            'start_using_at' => 'required|date',
            'patient_exists' => 'required|' . Rule::FLG_INTEGER,
            'patient_id' => self::REQUIRED_ON_PATIENT . '|' . Rule::VALUE_POSITIVE_NON_ZERO,
            'patient_code' => 'nullable|' . Rule::VALUE_STRING,
            'patient_height' => self::REQUIRED_ON_PATIENT . '|' . Rule::VALUE_POSITIVE . '|max:999',
            'patient_weight' => 'nullable|' . Rule::VALUE_POSITIVE . '|max:999',
            'patient_gender' => self::REQUIRED_ON_PATIENT . '|' . Rule::intRange(1, 2),
            'patient_value_exists' => 'required|' . Rule::FLG_INTEGER,
            'patient_value_registered_at' => self::REQUIRED_ON_PATIENT_VALUE . '|date',
            'opt_out_flg' => 'nullable|' . Rule::FLG_INTEGER,
            'age' => 'nullable|' . Rule::VALUE_POSITIVE_INTEGER,
            'vent_disease_name' => 'nullable|' . Rule::VALUE_STRING,
            'other_disease_name_1' => 'nullable|' . Rule::VALUE_STRING,
            'other_disease_name_2' => 'nullable|' . Rule::VALUE_STRING,
            'used_place' => 'nullable|' . Rule::VALUE_POSITIVE_INTEGER,
            'hospital' => 'nullable|' . Rule::VALUE_STRING,
            'national' => 'nullable|' . Rule::VALUE_STRING,
            'discontinuation_at' => 'nullable|date',
            'outcome' => 'nullable|' . Rule::VALUE_POSITIVE_INTEGER,
            'treatment' => 'nullable|' . Rule::VALUE_POSITIVE_INTEGER,
            'adverse_event_flg' => 'nullable|' . Rule::FLG_INTEGER,
            'adverse_event_contents' => 'nullable|' . Rule::VALUE_TEXT,
            'ventilator_value_exists' => 'required|' . Rule::FLG_INTEGER,
            'appkey_id' => self::REQUIRED_ON_VENTILATOR_VALUE . '|' . Rule::VALUE_POSITIVE_NON_ZERO,
            'ventilator_value_registered_at' => self::REQUIRED_ON_VENTILATOR_VALUE . '|date',
            'height' => self::REQUIRED_ON_VENTILATOR_VALUE . '|' . Rule::VALUE_POSITIVE . '|max:999',
            'weight' => 'nullable|' . Rule::VALUE_POSITIVE . '|max:999',
            'gender' => self::REQUIRED_ON_VENTILATOR_VALUE . '|' . Rule::intRange(1, 2),
            'ideal_weight' => self::REQUIRED_ON_VENTILATOR_VALUE . '|' . Rule::VALUE_POSITIVE,
            'airway_pressure' => self::REQUIRED_ON_VENTILATOR_VALUE . '|' . Rule::VALUE_POSITIVE,
            'total_flow' => self::REQUIRED_ON_VENTILATOR_VALUE . '|' . Rule::VALUE_POSITIVE,
            'air_flow' => self::REQUIRED_ON_VENTILATOR_VALUE . '|' . Rule::VALUE_POSITIVE,
            'o2_flow' => self::REQUIRED_ON_VENTILATOR_VALUE . '|' . Rule::VALUE_POSITIVE,
            'rr' => self::REQUIRED_ON_VENTILATOR_VALUE . '|' . Rule::VALUE_POSITIVE,
            'expiratory_time' => self::REQUIRED_ON_VENTILATOR_VALUE . '|' . Rule::VALUE_POSITIVE,
            'inspiratory_time' => self::REQUIRED_ON_VENTILATOR_VALUE . '|' . Rule::VALUE_POSITIVE,
            'vt_per_kg' => self::REQUIRED_ON_VENTILATOR_VALUE . '|' . Rule::VALUE_POSITIVE,
            'predicted_vt' => self::REQUIRED_ON_VENTILATOR_VALUE . '|' . Rule::VALUE_POSITIVE,
            'estimated_vt' => self::REQUIRED_ON_VENTILATOR_VALUE . '|' . Rule::VALUE_POSITIVE,
            'estimated_mv' => self::REQUIRED_ON_VENTILATOR_VALUE . '|' . Rule::VALUE_POSITIVE,
            'estimated_peep' => self::REQUIRED_ON_VENTILATOR_VALUE . '|' . Rule::VALUE_POSITIVE,
            'fio2' => self::REQUIRED_ON_VENTILATOR_VALUE . '|' . Rule::VALUE_POSITIVE,
            'status_use' => 'nullable|' . Rule::VALUE_POSITIVE_INTEGER,
            'status_use_other' => 'nullable|' . Rule::VALUE_STRING,
            'spo2' => 'nullable|' . Rule::VALUE_POSITIVE,
            'etco2' => 'nullable|' . Rule::VALUE_POSITIVE,
            'pao2' => 'nullable|' . Rule::VALUE_POSITIVE,
            'paco2' => 'nullable|' . Rule::VALUE_POSITIVE,
            'fixed_flg' => self::REQUIRED_ON_VENTILATOR_VALUE . '|' . Rule::FLG_INTEGER,
            'fixed_at' => 'nullable|date',
            'confirmed_flg' => self::REQUIRED_ON_VENTILATOR_VALUE . '|' . Rule::FLG_INTEGER,
            'confirmed_at' => 'nullable|date',
        ];
    }

    protected function bind($input)
    {
        $this->org_ventilator_id = intval($input['ventilator_id']);
        $this->gs1_code = strval($input['gs1_code']);
        $this->serial_number = strval($input['serial_number']);
        $this->city = isset($input['city']) ? strval($input['city']) : null;
        $this->qr_read_at = Support\DateUtil::parseToDatetime($input['qr_read_at']);
        $this->expiration_date = isset($input['expiration_date']) ? Support\DateUtil::parseToDate($input['expiration_date']) : null;
        $this->start_using_at = Support\DateUtil::parseToDatetime($input['start_using_at']);

        $this->patient_exists = intval($input['patient_exists']);
        if ($this->patient_exists) {
            $this->org_patient_id = isset($input['patient_id']) ? strval($input['patient_id']) : null;
            $this->patient_code = isset($input['patient_code']) ? strval($input['patient_code']) : null;
            $this->patient_height = isset($input['patient_height']) ? strval($input['patient_height']) : null;
            $this->patient_weight = isset($input['patient_weight']) ? strval($input['patient_weight']) : null;
            $this->patient_gender = isset($input['patient_gender']) ? strval($input['patient_gender']) : null;
        }

        $this->patient_value_exists = intval($input['patient_value_exists']);
        if ($this->patient_value_exists) {
            $this->patient_value_registered_at = !empty($input['patient_value_registered_at']) ? Support\DateUtil::parseToDatetime($input['patient_value_registered_at']) : null;
            $this->age = isset($input['age']) ? strval($input['age']) : null;
            $this->vent_disease_name = isset($input['vent_disease_name']) ? strval($input['vent_disease_name']) : null;
            $this->other_disease_name_1 = isset($input['other_disease_name_1']) ? strval($input['other_disease_name_1']) : null;
            $this->other_disease_name_2 = isset($input['other_disease_name_2']) ? strval($input['other_disease_name_2']) : null;
            $this->used_place = isset($input['used_place']) ? intval($input['used_place']) : null;
            $this->hospital = isset($input['hospital']) ? strval($input['hospital']) : null;
            $this->national = isset($input['national']) ? strval($input['national']) : null;
            $this->discontinuation_at = !empty($input['discontinuation_at']) ? Support\DateUtil::parseToDatetime($input['discontinuation_at']) : null;
            $this->outcome = isset($input['outcome']) ? intval($input['outcome']) : null;
            $this->treatment = isset($input['treatment']) ? intval($input['treatment']) : null;
            $this->adverse_event_flg = isset($input['adverse_event_flg']) ? intval($input['adverse_event_flg']) : null;
            $this->adverse_event_contents = isset($input['adverse_event_contents']) ? strval($input['adverse_event_contents']) : null;
            $this->opt_out_flg = isset($input['opt_out_flg']) ? intval($input['opt_out_flg']) : null;
        }

        $this->ventilator_value_exists = intval($input['ventilator_value_exists']);
        if ($this->ventilator_value_exists) {
            $this->appkey_id = isset($input['appkey_id']) ? strval($input['appkey_id']) : null;
            $this->ventilator_value_registered_at = !empty($input['ventilator_value_registered_at']) ? Support\DateUtil::parseToDatetime($input['ventilator_value_registered_at']) : null;
            $this->height = isset($input['height']) ? strval($input['height']) : null;
            $this->weight = isset($input['weight']) ? strval($input['weight']) : null;
            $this->gender = isset($input['gender']) ? strval($input['gender']) : null;
            $this->ideal_weight = isset($input['ideal_weight']) ? strval($input['ideal_weight']) : null;
            $this->airway_pressure = isset($input['airway_pressure']) ? strval($input['airway_pressure']) : null;
            $this->total_flow = isset($input['total_flow']) ? strval($input['total_flow']) : null;
            $this->air_flow = isset($input['air_flow']) ? strval($input['air_flow']) : null;
            $this->o2_flow = isset($input['o2_flow']) ? strval($input['o2_flow']) : null;
            $this->rr = isset($input['rr']) ? strval($input['rr']) : null;
            $this->expiratory_time = isset($input['expiratory_time']) ? strval($input['expiratory_time']) : null;
            $this->inspiratory_time = isset($input['inspiratory_time']) ? strval($input['inspiratory_time']) : null;
            $this->vt_per_kg = isset($input['vt_per_kg']) ? strval($input['vt_per_kg']) : null;
            $this->predicted_vt = isset($input['predicted_vt']) ? strval($input['predicted_vt']) : null;
            $this->estimated_vt = isset($input['estimated_vt']) ? strval($input['estimated_vt']) : null;
            $this->estimated_mv = isset($input['estimated_mv']) ? strval($input['estimated_mv']) : null;
            $this->estimated_peep = isset($input['estimated_peep']) ? strval($input['estimated_peep']) : null;
            $this->fio2 = isset($input['fio2']) ? strval($input['fio2']) : null;
            $this->status_use = isset($input['status_use']) ? intval($input['status_use']) : null;
            $this->status_use_other = isset($input['status_use_other']) ? strval($input['status_use_other']) : null;
            $this->spo2 = isset($input['spo2']) ? strval($input['spo2']) : null;
            $this->etco2 = isset($input['etco2']) ? strval($input['etco2']) : null;
            $this->pao2 = isset($input['pao2']) ? strval($input['pao2']) : null;
            $this->paco2 = isset($input['paco2']) ? strval($input['paco2']) : null;
            $this->fixed_flg = isset($input['fixed_flg']) ? intval($input['fixed_flg']) : null;
            $this->fixed_at = !empty($input['fixed_at']) ? Support\DateUtil::parseToDatetime($input['fixed_at']) : null;
            $this->confirmed_flg = isset($input['confirmed_flg']) ? intval($input['confirmed_flg']) : null;
            $this->confirmed_at = !empty($input['confirmed_at']) ? Support\DateUtil::parseToDatetime($input['confirmed_at']) : null;
        }
    }
}
