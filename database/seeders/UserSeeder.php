<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Admin Gemini',
                'email' => 'admin@gemini.edu.vn',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'avatar' => 'admin.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Nguyễn Văn Vãi',
                'email' => 'vai@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'avatar' => 'vai.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Trần Thị Dung',
                'email' => 'dung@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'avatar' => 'dung.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Lê Văn Tiết',
                'email' => 'tiet@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'avatar' => 'tiet.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Phạm Minh Tuấn',
                'email' => 'tuan@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'avatar' => 'tuan.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
