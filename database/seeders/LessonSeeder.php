<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('lessons')->insert([
            [
                'id' => 1,
                'course_id' => 1,
                'title' => 'Cài đặt môi trường Laravel',
                'video_url' => 'https://youtu.be/abc1',
                'video_type' => 'youtube',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'course_id' => 1,
                'title' => 'Cấu trúc thư mục dự án',
                'video_url' => 'https://youtu.be/abc2',
                'video_type' => 'youtube',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'course_id' => 1,
                'title' => 'Tạo Controller đầu tiên',
                'video_url' => 'https://youtu.be/abc3',
                'video_type' => 'youtube',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'course_id' => 1,
                'title' => 'Kết nối Database MySQL',
                'video_url' => 'https://youtu.be/abc4',
                'video_type' => 'youtube',
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'course_id' => 1,
                'title' => 'Xây dựng trang Login',
                'video_url' => 'https://youtu.be/abc5',
                'video_type' => 'youtube',
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}