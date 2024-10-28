<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure roles are inserted only if they do not already exist
        $roles = ['admin', 'user_stok', 'marketing', 'management'];
        foreach ($roles as $role) {
            $roleId = DB::table('roles')->where('name', $role)->value('id');
            if (!$roleId) {
                $roleId = DB::table('roles')->insertGetId(['name' => $role]);
            }
        }

        // Insert users with role IDs
        $adminRole = DB::table('roles')->where('name', 'admin')->value('id');
        $userStokRole = DB::table('roles')->where('name', 'user_stok')->value('id');
        $marketingRole = DB::table('roles')->where('name', 'marketing')->value('id');
        $managementRole = DB::table('roles')->where('name', 'management')->value('id');

        DB::table('users')->insert([
            'name' => 'Yoga Prasetyo',
            'username' => 'YogaAdmin',
            'password' => Hash::make('admin1'),
            'role_id' => $adminRole,
        ]);

        DB::table('users')->insert([
            'name' => 'Imam Wardana',
            'username' => 'ImamUser',
            'password' => Hash::make('userstok1'),
            'role_id' => $userStokRole,
        ]);

        DB::table('users')->insert([
            'name' => 'Ericha Zevtyana Putri',
            'username' => 'ErichaUser',
            'password' => Hash::make('marketing1'),
            'role_id' => $marketingRole,
        ]);

        DB::table('users')->insert([
            'name' => 'Fitri Widya Handayani',
            'username' => 'FitriUser',
            'password' => Hash::make('management1'),
            'role_id' => $managementRole,
        ]);

        // Insert categories if they do not already exist
        $categories = ['HANDPHONE', 'KOMPUTER', 'MOBIL', 'MOTOR', 'KABEL'];
        foreach ($categories as $category) {
            if (!DB::table('kategoris')->where('name', $category)->exists()) {
                DB::table('kategoris')->insert(['name' => $category]);
            }
        }

        // Insert units if they do not already exist
        $units = ['PCS', 'KILOGRAMS', 'METER', 'UNIT'];
        foreach ($units as $unit) {
            if (!DB::table('satuans')->where('name', $unit)->exists()) {
                DB::table('satuans')->insert(['name' => $unit]);
            }
        }

        // Insert purposes if they do not already exist
        $purposes = ['Dipinjam', 'Instalasi', 'Di Pakai Sendiri'];
        foreach ($purposes as $purpose) {
            if (!DB::table('keperluans')->where('name', $purpose)->exists()) {
                DB::table('keperluans')->insert(['name' => $purpose]);
            }
        }

        // Insert barang
        DB::table('barangs')->insert([
            [
                'kode' => 'HP001',
                'name' => 'Samsung Galaxy S21',
                'kategori_id' => DB::table('kategoris')->where('name', 'HANDPHONE')->value('id'),
                'satuan_id' => DB::table('satuans')->where('name', 'PCS')->value('id'),
                'merek' => 'Samsung',
                'stok' => 100,
            ],
            [
                'kode' => 'CP001',
                'name' => 'Lenovo ThinkPad X1',
                'kategori_id' => DB::table('kategoris')->where('name', 'KOMPUTER')->value('id'),
                'satuan_id' => DB::table('satuans')->where('name', 'PCS')->value('id'),
                'merek' => 'Lenovo',
                'stok' => 100,
            ],
            [
                'kode' => 'MP001',
                'name' => 'Toyota Avanza',
                'kategori_id' => DB::table('kategoris')->where('name', 'MOBIL')->value('id'),
                'satuan_id' => DB::table('satuans')->where('name', 'PCS')->value('id'),
                'merek' => 'Toyota',
                'stok' => 100,
            ],
            [
                'kode' => 'MT001',
                'name' => 'Honda CBR 150R',
                'kategori_id' => DB::table('kategoris')->where('name', 'MOTOR')->value('id'),
                'satuan_id' => DB::table('satuans')->where('name', 'PCS')->value('id'),
                'merek' => 'Honda',
                'stok' => 100,
            ],
            [
                'kode' => 'KB001',
                'name' => 'Cable HDMI 3m',
                'kategori_id' => DB::table('kategoris')->where('name', 'KABEL')->value('id'),
                'satuan_id' => DB::table('satuans')->where('name', 'METER')->value('id'),
                'merek' => 'HDMI',
                'stok' => 100,
            ],
        ]);
    }
}