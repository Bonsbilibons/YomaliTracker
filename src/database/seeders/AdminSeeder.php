<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::query()->where('email', '=', 'yomali_admin@example.com')->exists()) {
            return;
        }

        $admin = new User();

        $admin->fill([
            'name' => 'Yomali Admin',
            'email' => config('app.default_admin_email'),
            'password' => Hash::make(config('app.default_admin_password')),
        ]);

        $admin->save();
    }
}
