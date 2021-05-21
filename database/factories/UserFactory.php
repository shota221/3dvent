<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\User;
use App\Repositories as Repos;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $organization = Organization::inRandomOrder()->first();
        $name = $this->faker->unique()->userName;
        $password = 'password';
        echo $name.'@'.$organization->code.' | '.$password."\n";
        return [
            'name' => $name,
            'organization_id' => $organization->id,
            'email' => $this->faker->safeEmail,
            'authority' => rand(),
            'password' => Hash::make($password),
        ];
    }
}
