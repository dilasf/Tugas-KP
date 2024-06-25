<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Aunul Mabud',
            'email' => 'admin@example.com',
            // 'nuptk' => '8933771672130222',
            // 'NIP' => '-',
            'password' => Hash::make('password123'),
        ]);

        $admin->assignRole('admin');
    }
}
