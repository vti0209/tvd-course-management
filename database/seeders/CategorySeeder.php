<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'id' => 1,
                'name' => 'Lập trình Web',
                'slug' => 'lap-trinh-web',
                'description' => 'Học các công nghệ FE và BE mới nhất',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Thiết kế đồ họa',
                'slug' => 'thiet-ke-do-hoa',
                'description' => 'Sáng tạo với Photoshop và Illustrator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Marketing Online',
                'slug' => 'marketing-online',
                'description' => 'Chiến dịch quảng cáo và SEO chuyên nghiệp',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Kỹ năng mềm',
                'slug' => 'ky-nang-mem',
                'description' => 'Giao tiếp và quản lý thời gian hiệu quả',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Ngoại ngữ',
                'slug' => 'ngoai-ngu',
                'description' => 'Tiếng Anh giao tiếp cho người đi làm',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}