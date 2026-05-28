<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $staffRole = Role::firstOrCreate(['name' => 'Staff']);

        $admin = User::updateOrCreate(
            ['email' => 'admin@sipusaka.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password123'),
            ]
        );
        $admin->syncRoles([$adminRole]);

        $staff = User::updateOrCreate(
            ['email' => 'staff@sipusaka.com'],
            [
                'name' => 'Staff Perpustakaan',
                'password' => Hash::make('password123'),
            ]
        );
        $staff->syncRoles([$staffRole]);
    }
}