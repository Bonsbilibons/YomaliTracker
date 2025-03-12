<?php

namespace Database\Seeders;

use App\Models\Plugin;
use Illuminate\Database\Seeder;;

class PluginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $identifier = 'yomaliTR12032025';
        if (Plugin::query()->where('identifier', '=', $identifier)->exists()) {
            return;
        }

        $plugin = new Plugin();

        $plugin->fill([
            'name' => 'YomaliTracker',
            'host' => 'localhost',
            'identifier' => $identifier,
            'period' => '00:00:01'
        ]);

        $plugin->save();
    }
}
