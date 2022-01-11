<?php

namespace Tests\Unit;

use App\Http\Forms\Api as Form;
use App\Services\Api as Service;
use App\Services\Support\Converter;
use App\Services\Support\Logic;
use Tests\TestCase;

class CalcTest extends TestCase
{
    use Logic\CalculationLogic;

    private $service;

    function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->service = new Service\CalcService;
    }

    /**
     * @test
     *
     * @return void
     */
    public function defaultFlow()
    {
        var_dump($this->service->getDefaultFlow());
        $this->assertTrue(true);
    }

    /**
     * @test
     *
     * @return void
     */
    public function estimatedData()
    {
        $req = [
            'airway_pressure' => 10,
            'air_flow' => 7.0,
            'o2_flow' => 3.0,
        ];
        $form = new Form\CalcEstimatedDataForm($req);
        $res = $this->service->getEstimatedData($form);
        var_dump($res);
        $this->assertTrue(true);
    }

    /**
     * @test
     *
     * @return void
     */
    public function ieManual()
    {
        $req = ['data' => [
            [
                'e' => '1.17',
                'respirations_per_10sec' => '3.42'
            ],
            [
                'e' => '1.19',
                'respirations_per_10sec' => '3.44'
            ]
        ]];
        $expected_res = Converter\IeConverter::convertToIeResult(1.73, 1.18, 20.6, 0.7);

        var_dump($expected_res);
        $form = new Form\CalcIeManualForm($req);
        $res = $this->service->getIeManual($form);
        var_dump($res);
        $this->assertEquals($expected_res, $res);
    }
}
