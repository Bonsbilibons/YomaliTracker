<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminSeeder = new AdminSeeder();
        $adminSeeder->run();

        $pluginSeeder = new PluginSeeder();
        $pluginSeeder->run();
    }
}
