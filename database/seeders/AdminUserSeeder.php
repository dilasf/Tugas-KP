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
        // $admin = User::create([
        //     'name' => 'Aunul Ma\'bud',
        //     'email' => 'admin@gmail.com',
        //     'password' => Hash::make('password123'),
        // ]);

        // // Assign role admin ke user
        // $admin->assignRole('admin');
        $role = Role::where('name', 'admin')->first();
if (!$role) {
    // Buat role 'admin' jika belum ada
    $role = Role::create(['name' => 'admin']);
}

// Buat user dengan role_id yang sesuai
$admin = User::create([
    'teacher_id' => null,
    'name' => 'Aunul Mabud',
    'email' => 'admin@example.com',
    'nuptk' => '8933771672130222',
    'nip' => '-',
    'password' => Hash::make('password123'),
    'role_id' => $role->id, // Atur role_id sesuai dengan ID role yang dibuat atau ditemukan
]);

// Pastikan role_id terisi sebelum menugaskan role
if ($admin->role_id === $role->id) {
    // Tugaskan role 'admin' ke user
    $admin->assignRole($role);
}
        // $admin->assignRole('admin');

        // // Buat user guru mapel baru
        // $teacher = User::create([
        //     'name' => 'Putri Purnima Dewi',
        //     'email' => 'teacher@gmail.com',
        //     'password' => Hash::make('password123'),
        // ]);

        // // Assign role guru_mapel ke user
        // $teacher->assignRole('guru_mapel');
    }
}
