<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;

use App\Repositories as Repos;

/**
 * 組織のみ生成。組織ユーザも生成する場合はDatabaseSeederを回せばok。
 * ./composer.phar dump-autoload
 * ./artisan db:seed --class=OrganizationSeeder --env=local
 */
class OrganizationSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        echo 'NAME | CODE'."\n";
        Organization::factory(5)->create();
    }
}