<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;      // ✅ Add this
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Administrator',
                'nip' => '19870.912001',
                'password' => Hash::make('admin123'),
                'job_title' => 'System Administrator',
                'email' => 'admin@example.com',
                'phone' => '081234567890',
                'role_id' => 1, // Admin
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'CSO',
                'nip' => '19870.912002',
                'password' => Hash::make('user123'),
                'job_title' => 'CSO',
                'email' => 'cso@example.com',
                'phone' => '081298765432',
                'role_id' => 2, // CSO
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
