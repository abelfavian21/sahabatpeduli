<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Atur ADMIN_EMAIL dan ADMIN_PASSWORD di .env sebelum menjalankan seeder ini.
        $u = User::firstOrNew(['email' => env('ADMIN_EMAIL', 'admin@sahabatpeduli.id')]);
        $u->forceFill([
            'name' => 'Admin',
            'password' => Hash::make(env('ADMIN_PASSWORD', 'ganti-password-ini')),
            'is_admin' => true,
        ])->save();
    }
}
