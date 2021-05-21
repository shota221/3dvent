<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Organization::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->city . '立病院';
        $code = implode($this->faker->unique()->words());
        echo $name.' | '.$code."\n";
        return [
            'name' => $name,
            'code' => $code,
            'representative_name' => $this->faker->unique()->userName,
            'representative_email' => $this->faker->unique()->safeEmail,
        ];
    }
}
