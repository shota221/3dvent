<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * 組織・組織ユーザ順に生成
 * ./composer.phar dump-autoload
 * ./artisan db:seed --class=DatabaseSeeder --env=local
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(OrganizationSeeder::class);
        $this->call(UserSeeder::class);
    }
}
