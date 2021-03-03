<?php

namespace App\Http\Forms\Api;

use App\Http\Forms\ValidationRule as Rule;

use App\Http\Forms\BaseForm;

class VentilatorValueCreateForm extends BaseForm
{
    public $ventilator_id;

    public $patient_id;

    public $airway_pressure;

    public $air_flow;

    public $o2_flow;

    public $rr;

    public $i_avg;

    public $e_avg;

    public $predicted_vt;

    public $estimated_vt;

    public $estimated_mv;

    public $estimated_peep;

    public $fio2;

    public $total_flow;

    public $user_id;

    public $vt_per_kg;

    public $appkey_id;
    
    protected function validationRule()
    {
        return [
            'ventilator_id' => 'required|'.Rule::VALUE_POSITIVE_INTEGER,

            'patient_id' => 'required|'.Rule::VALUE_POSITIVE_INTEGER,

            'airway_pressure' => 'required|'.Rule::VALUE_POSITIVE,

            'air_flow' => 'required|'.Rule::VALUE_POSITIVE,

            'o2_flow' => 'required|'.Rule::VALUE_POSITIVE,

            'rr' => 'required|'.Rule::VALUE_POSITIVE,

            'i_avg' => 'required|'.Rule::VALUE_POSITIVE,

            'e_avg' => 'required|'.Rule::VALUE_POSITIVE,

            'predicted_vt' => 'required|'.Rule::VALUE_POSITIVE,
        ];  
    }

    protected function bind($input)
    {
        $this->ventilator_id = $input['ventilator_id'];
        
        $this->patient_id = $input['patient_id'];
        
        $this->airway_pressure = $input['airway_pressure'];
        
        $this->air_flow = $input['air_flow'];
        
        $this->o2_flow = $input['o2_flow'];
        
        $this->rr = $input['rr'];
        
        $this->i_avg = $input['i_avg'];
        
        $this->e_avg = $input['e_avg'];
        
        $this->predicted_vt = $input['predicted_vt'];

    }
}