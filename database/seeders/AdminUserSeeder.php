<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user admin baru
        $admin = User::create([
            'name' => 'Aunul Ma\'bud',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
        ]);

        // Assign role admin ke user
        $admin->assignRole('admin');

        // Buat user guru mapel baru
        $teacher = User::create([
            'name' => 'Putri Purnima Dewi',
            'email' => 'teacher@gmail.com',
            'password' => Hash::make('password123'),
        ]);

        // Assign role guru_mapel ke user
        $teacher->assignRole('guru_mapel');
    }
}
