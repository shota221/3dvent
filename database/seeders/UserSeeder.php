<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;

use App\Repositories as Repos;
use League\Flysystem\Adapter\Local;
use Faker\Generator as Faker;

/**
 * 組織ユーザのみ生成。組織も生成する場合はDatabaseSeederを回せばok。
 * ./composer.phar dump-autoload
 * ./artisan db:seed --class=UserSeeder --env=local
 */
class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        echo 'ACCOUNT_NAME | PASSWORD'."\n";
        User::factory(20)->create();
    }
}