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
        // // Buat user admin baru
        $role = Role::where('name', 'admin')->first();
        if (!$role) {
            // Buat role 'admin' jika belum ada
            $role = Role::create(['name' => 'admin']);
        }

        // Buat user dengan role_id yang sesuai
        $admin = User::create([
            'teacher_id' => null,
            'name' => 'Aunul Mabud',
            'email' => 'admin@gmail.com',
            'nuptk' => '8933771672130222',
            'nip' => '-',
            'password' => Hash::make('password123'),
            'role_id' => $role->id,
        ]);

        // Pastikan role_id terisi sebelum menugaskan role
        if ($admin->role_id === $role->id) {
            // Tugaskan role 'admin' ke user
            $admin->assignRole($role);
        }

    }
}
