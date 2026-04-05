<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('course_user')->insert([
            [
                'user_id' => 2,
                'course_id' => 1,
                'enrolled_at' => now(),
                'status' => 'active',
            ],
            [
                'user_id' => 2,
                'course_id' => 2,
                'enrolled_at' => now(),
                'status' => 'active',
            ],
            [
                'user_id' => 3,
                'course_id' => 1,
                'enrolled_at' => now(),
                'status' => 'completed',
            ],
            [
                'user_id' => 4,
                'course_id' => 3,
                'enrolled_at' => now(),
                'status' => 'active',
            ],
            [
                'user_id' => 5,
                'course_id' => 4,
                'enrolled_at' => now(),
                'status' => 'active',
            ],
        ]);
    }
}
