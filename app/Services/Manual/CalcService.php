<?php

namespace App\Services\Manual;

use App\Http\Forms\Manual as Form;
use App\Services\Support\Logic;
use App\Services\Support\Converter;

class CalcService
{
    use Logic\CalculationLogic;

    public function fetchFio2(Form\CalcFio2Form $form)
    {
        $fio2        = $this->calcFio2(floatval($form->air_flow), floatval($form->o2_flow));
        $rouded_fio2 = $this->roundOff($fio2, 'fio2');

        return Converter\VentilatorValueConverter::convertToFio2Result($rouded_fio2);

    }
}
