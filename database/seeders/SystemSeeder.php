<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run()
    {
        // --- 1. TẠO ADMIN ---
        DB::table('users')->insert([
            'username' => 'admin_hethong',
            'password' => Hash::make('admin123'),
            'email' => 'admin@gmail.com',
            'full_name' => 'Quản Trị Viên Cao Cấp',
            'role' => 'admin',
            'status' => 'active',
            'created_at' => now(),
        ]);

        // --- 2. TẠO PROVIDERS (Giảng viên) ---
        $providerIds = [];
        $providers = [
            ['u' => 'son.dang', 'name' => 'Sơn Đặng F8', 'email' => 'sondang@f8.edu.vn'],
            ['u' => 'hoang.dev', 'name' => 'Hoàng Lập Trình', 'email' => 'hoangdev@gmail.com'],
            ['u' => 'huong.design', 'name' => 'Thanh Hương Design', 'email' => 'huongthanh@gmail.com'],
        ];

        foreach ($providers as $p) {
            $id = DB::table('users')->insertGetId([
                'username' => $p['u'],
                'password' => Hash::make('123456'),
                'email' => $p['email'],
                'full_name' => $p['name'],
                'role' => 'provider',
                'status' => 'active',
                'created_at' => now(),
            ]);
            $providerIds[] = $id;

            // Tạo profile cho Provider
            DB::table('provider_profiles')->insert([
                'user_id' => $id,
                'cv_file' => 'uploads/cv/profile_'.$p['u'].'.pdf',
                'bio' => 'Giảng viên chuyên sâu với 5 năm kinh nghiệm đào tạo thực chiến.',
                'bank_account' => '999988887777',
                'approved_at' => now(),
            ]);
        }

        // --- 3. TẠO CATEGORIES (Danh mục) ---
        $categories = [
            ['name' => 'Lập trình Web', 'slug' => 'lap-trinh-web'],
            ['name' => 'Lập trình Di động', 'slug' => 'lap-trinh-di-dong'],
            ['name' => 'Thiết kế đồ họa', 'slug' => 'thiet-ke-do-hoa'],
            ['name' => 'Marketing Online', 'slug' => 'marketing-online'],
        ];
        foreach ($categories as $cat) {
            DB::table('categories')->insert(array_merge($cat, ['created_at' => now()]));
        }

        // --- 4. TẠO KHÓA HỌC THẬT (Courses) ---
        // Khóa học 1: Lập trình PHP Laravel
        $course1Id = DB::table('courses')->insertGetId([
            'provider_id' => $providerIds[0],
            'category_id' => 1,
            'title' => 'Lập trình Laravel từ cơ bản đến nâng cao (Dự án thực tế)',
            'description' => 'Khóa học giúp bạn làm chủ Framework Laravel mạnh mẽ nhất của PHP qua việc xây dựng dự án Course Management.',
            'price' => 1200000,
            'thumbnail' => 'laravel_course.jpg',
            'status' => 'active',
            'created_at' => now(),
        ]);

        // Khóa học 2: Thiết kế UI/UX
        $course2Id = DB::table('courses')->insertGetId([
            'provider_id' => $providerIds[2],
            'category_id' => 3,
            'title' => 'Thiết kế UI/UX hiện đại với Figma',
            'description' => 'Học cách tư duy thiết kế người dùng và sử dụng thành thạo Figma chỉ trong 4 tuần.',
            'price' => 850000,
            'thumbnail' => 'figma_uiux.png',
            'status' => 'active',
            'created_at' => now(),
        ]);

        // --- 5. TẠO CHƯƠNG VÀ BÀI HỌC CHO KHÓA LARAVEL ---
        $chapters = [
            'Chương 1: Cài đặt và Cấu trúc dự án',
            'Chương 2: Làm việc với Database & Migration',
            'Chương 3: Xây dựng chức năng Authentication',
        ];

        foreach ($chapters as $index => $cTitle) {
            $chapterId = DB::table('chapters')->insertGetId([
                'course_id' => $course1Id,
                'title' => $cTitle,
                'sort_order' => $index + 1,
            ]);

            // Mỗi chương tạo 2 bài học
            for ($i = 1; $i <= 2; $i++) {
                DB::table('lessons')->insert([
                    'chapter_id' => $chapterId,
                    'title' => 'Bài học số ' . $i . ' của ' . $cTitle,
                    'content_type' => 'video',
                    'content_url' => 'https://youtube.com/watch?v=sample_video',
                    'sort_order' => $i,
                ]);
            }
        }

        // --- 6. TẠO USER HỌC VIÊN MẪU ---
        for ($i = 1; $i <= 5; $i++) {
            $uId = DB::table('users')->insertGetId([
                'username' => 'hocvien' . $i,
                'password' => Hash::make('123456'),
                'email' => 'hocvien' . $i . '@gmail.com',
                'full_name' => 'Học Viên ' . $i,
                'role' => 'user',
                'status' => 'active',
                'created_at' => now(),
            ]);

            // Cho mỗi học viên mua ngẫu nhiên khóa học 1 hoặc 2
            DB::table('enrollments')->insert([
                'user_id' => $uId,
                'course_id' => ($i % 2 == 0) ? $course1Id : $course2Id,
                'payment_status' => 'paid',
                'price_at_purchase' => ($i % 2 == 0) ? 1200000 : 850000,
                'enrolled_at' => now(),
            ]);
        }
    }
}