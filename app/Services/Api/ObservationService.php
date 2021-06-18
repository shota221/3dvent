<?php

namespace App\Services\Api;

use App\Exceptions;
use App\Models;
use App\Models\HistoryBaseModel;
use App\Models\Organization;
use App\Models\VentilatorValue;
use App\Http\Forms\Api as Form;
use App\Repositories as Repos;
use App\Services\Support\Converter;
use App\Http\Response as Response;

class ObservationService
{
    
    /**
     * 指定期間にに操作された（CUD）患者、機器の観察研究データ履歴数取得
     * 
     * @param  Form\ObservationCountForm $form [description]
     * @return [type]                          [description]
     */
    public function count(Form\ObservationCountForm $form) 
    {
        $search_values = $this->buildObservationSearchValues($form);
        
        $ventilator_observed_count = Repos\VentilatorValueHistoryRepository::countBySearchValues($search_values);

        $patient_observed_count = Repos\PatientValueHistoryRepository::countBySearchValues($search_values);
 

        $ventilator_bug_count = Repos\VentilatorBugRepository::countBySearchValues($search_values);

        return Converter\ObservationConverter::convertToObservationCount($ventilator_observed_count, $patient_observed_count, $ventilator_bug_count);
    }

    /**
     * 指定期間に操作された（CUD）患者観察研究データ一覧取得
     * 
     * @param  Form\PatientObservationListForm $form [description]
     * @return [type]                                [description]
     */
    public function getPatientList(Form\PatientObservationListForm $form) 
    {   
        $search_values = $this->buildObservationSearchValues($form);

        $patient_observed_values = Repos\PatientValueHistoryRepository::findBySeachValuesAndLimitOffsetOrderByPatientValueRegisteredAtAscAndCreatedAtAsc($search_values, $form->limit, $form->offset);

        $data = array_map(
            function ($patient_observed_value) {
                if ($patient_observed_value->operation === HistoryBaseModel::CREATE) {
                    $patient_observed_value->operation = HistoryBaseModel::CREATE_STATUS_NAME;
                } else {
                    $patient_observed_value->operation = HistoryBaseModel::DELETE_STATUS_NAME;
                };
                return Converter\ObservationConverter::convertToPatientObservedValueListElm($patient_observed_value);
            },
            $patient_observed_values->all()
        );

        return new Response\ListJsonResult($data);

    }

    /**
     * 指定期間に操作された（CUD）機器察研究データ一覧取得
     * 
     * @param  Form\VentilatorObservationListForm $form [description]
     * @return [type]                        　　　　　  [description]
     */
    public function getVentilatorList(Form\VentilatorObservationListForm $form) 
    {
        $search_values = $this->buildObservationSearchValues($form);

        $ventilator_observed_values = Repos\VentilatorValueHistoryRepository::findBySeachValuesAndLimitOffsetOrderByVentilatorValueRegisteredAtAscAndCreatedAtAsc($search_values, $form->limit, $form->offset);

        $data = array_map(
            function ($ventilator_observed_value) {
                if ($ventilator_observed_value->operation === HistoryBaseModel::CREATE) {
                    $ventilator_observed_value->operation = HistoryBaseModel::CREATE_STATUS_NAME;
                } else {
                    $ventilator_observed_value->operation = HistoryBaseModel::DELETE_STATUS_NAME;
                };

                if ($ventilator_observed_value->user_name === null) {
                    $ventilator_observed_value->user_name = "";
                }

                return Converter\ObservationConverter::convertToVentilatorObservedValueListElm($ventilator_observed_value);
            },
            $ventilator_observed_values->all()
        );

        return new Response\ListJsonResult($data);

    }

    /**
     * 指定期間に作成された機器不具合データ一覧取得
     * 
     * @param  Form\VentilatorBugListForm $form [description]
     * @return [type]                           [description]
     */
    public function getVentilatorBugList(Form\VentilatorBugListForm $form) 
    {
        $search_values = $this->buildObservationSearchValues($form);
     
        $ventilator_bugs = Repos\VentilatorBugRepository::findBySeachValuesAndLimitOffsetOrderByRegisteredAtAsc($search_values, $form->limit, $form->offset);

        $data = array_map(
            function ($ventilator_bug) {
                return Converter\ObservationConverter::convertToVentilatorBugListElm($ventilator_bug);
            },
            $ventilator_bugs->all()
        );

        return new Response\ListJsonResult($data);

    }


    private function buildObservationSearchValues($form) 
    {
        $search_values = [];
        
        $search_values['edcid'] = $form->edcid;

        if (! is_null($form->datetime_from)) $search_values['datetime_from'] = $form->datetime_from; 
        
        if (! is_null($form->datetime_to)) $search_values['datetime_to'] = $form->datetime_to; 

        $search_values['confirmed_flg'] = VentilatorValue::CONFIRM;
     
        $search_values['patient_obs_approved_flg'] = Organization::PATIENT_OBS_APPROVED;

        return $search_values;

    }

}
