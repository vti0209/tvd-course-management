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
            ['u' => 'tiet.ho', 'name' => 'Vanw Tiet', 'email' => 'tiet.ho@geminiacademy.edu.vn'],
            ['u' => 'dung.nguyen', 'name' => 'Dung Nguyen', 'email' => 'dung.nguyen@gmail.com'],
            ['u' => 'Vai.design', 'name' => 'Thị Vãi Design', 'email' => 'vai.design@gmail.com'],
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
            'duration' => 40,
            'thumbnail' => 'images/courses/Laravel_11_Fullstack.jpg',
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
            'duration' => 28,
            'thumbnail' => 'images/courses/UI_UX_Design_Pro.jpg',
            'status' => 'active',
            'created_at' => now(),
        ]);

        // Khóa học 3: Khóa học MIỄN PHÍ - Lập trình JavaScript Cơ bản
        $course3Id = DB::table('courses')->insertGetId([
            'provider_id' => $providerIds[1],
            'category_id' => 1,
            'title' => 'JavaScript Cơ bản - Khóa học miễn phí',
            'description' => 'Khóa học JavaScript từ zero đến hero hoàn toàn miễn phí. Học lập trình web với JavaScript thuần và ES6+.',
            'price' => 0,
            'duration' => 20,
            'thumbnail' => 'images/courses/1776605790_download.png',
            'status' => 'active',
            'created_at' => now(),
        ]);

        // --- 4. TẠO 8 KHÓA HỌC BỔ SUNG ---
        // Khóa học 4: React.js - Lập trình Frontend hiện đại
        $course4Id = DB::table('courses')->insertGetId([
            'provider_id' => $providerIds[0],
            'category_id' => 1,
            'title' => 'React.js - Lập trình Frontend hiện đại',
            'description' => 'Học React từ cơ bản đến nâng cao, xây dựng ứng dụng web single-page đơn giản và phức tạp.',
            'price' => 950000,
            'duration' => 32,
            'thumbnail' => 'images/courses/ReactJS.jpg',
            'status' => 'active',
            'created_at' => now(),
        ]);

        // Khóa học 5: Vue.js 3 - Framework linh hoạt
        $course5Id = DB::table('courses')->insertGetId([
            'provider_id' => $providerIds[1],
            'category_id' => 1,
            'title' => 'Vue.js 3 - Framework linh hoạt',
            'description' => 'Nắm vững Vue.js 3, Composition API, và các công cụ phát triển hiện đại. Hoàn hảo cho người mới bắt đầu.',
            'price' => 799000,
            'duration' => 24,
            'thumbnail' => 'images/courses/Vue.js_3_Advanced.jpg',
            'status' => 'active',
            'created_at' => now(),
        ]);

        // Khóa học 6: Node.js & Express - Backend Development
        $course6Id = DB::table('courses')->insertGetId([
            'provider_id' => $providerIds[0],
            'category_id' => 1,
            'title' => 'Node.js & Express - Backend Development',
            'description' => 'Tạo máy chủ web mạnh mẽ với Node.js và Express. Học REST API, databases, authentication và deployment.',
            'price' => 1100000,
            'duration' => 36,
            'thumbnail' => 'images/courses/1775536311_images.jpg',
            'status' => 'active',
            'created_at' => now(),
        ]);

        // Khóa học 7: Python - Lập trình từ cơ bản đến ứng dụng
        $course7Id = DB::table('courses')->insertGetId([
            'provider_id' => $providerIds[1],
            'category_id' => 1,
            'title' => 'Python - Lập trình từ cơ bản đến ứng dụng',
            'description' => 'Tìm hiểu Python, từ syntax cơ bản đến xây dựng ứng dụng thực tế. Bao gồm Web development với Django.',
            'price' => 1050000,
            'duration' => 40,
            'thumbnail' => 'images/courses/khoa-hoc-thuyet-trinh.jpg',
            'status' => 'active',
            'created_at' => now(),
        ]);

        // Khóa học 8: TypeScript - Hệ thống kiểu cho JavaScript
        $course8Id = DB::table('courses')->insertGetId([
            'provider_id' => $providerIds[0],
            'category_id' => 1,
            'title' => 'TypeScript - Hệ thống kiểu cho JavaScript',
            'description' => 'Nâng cấp kỹ năng JavaScript với TypeScript. Học kiểu dữ liệu, interface, decorator và design patterns.',
            'price' => 850000,
            'duration' => 28,
            'thumbnail' => 'images/courses/TypeScript.jpg',
            'status' => 'active',
            'created_at' => now(),
        ]);

        // Khóa học 9: Thiết kế Web với HTML5 & CSS3
        $course9Id = DB::table('courses')->insertGetId([
            'provider_id' => $providerIds[2],
            'category_id' => 3,
            'title' => 'Thiết kế Web với HTML5 & CSS3',
            'description' => 'Nền tảng của web development. Học HTML5 mới, CSS3 hiện đại, Flexbox, Grid và responsive design.',
            'price' => 599000,
            'duration' => 20,
            'thumbnail' => 'images/courses/1776605954_download (1).jfif',
            'status' => 'active',
            'created_at' => now(),
        ]);

        // Khóa học 10: Branding & Thiết kế Logo chuyên nghiệp
        $course10Id = DB::table('courses')->insertGetId([
            'provider_id' => $providerIds[2],
            'category_id' => 3,
            'title' => 'Branding & Thiết kế Logo chuyên nghiệp',
            'description' => 'Học tạo bộ nhận diện thương hiệu mạnh mẽ, thiết kế logo, color theory, typography, và brand guidelines.',
            'price' => 750000,
            'duration' => 25,
            'thumbnail' => 'images/courses/Thiet_ke_logo.jpg',
            'status' => 'active',
            'created_at' => now(),
        ]);

        // Khóa học 11: Digital Marketing - Chiến lược quảng cáo online
        $course11Id = DB::table('courses')->insertGetId([
            'provider_id' => $providerIds[1],
            'category_id' => 4,
            'title' => 'Digital Marketing - Chiến lược quảng cáo online',
            'description' => 'Hiểu biết sâu về SEO, SEM, Social Media Marketing, Email Marketing và Google Analytics để phát triển kinh doanh.',
            'price' => 899000,
            'duration' => 30,
            'thumbnail' => 'images/courses/khoa-hoc-quang-cao-facebook.jpg',
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

        // --- 6. TẠO CHƯƠNG VÀ BÀI HỌC CHO KHÓA JAVASCRIPT MIỄN PHÍ ---
        $jsChapters = [
            'Chương 1: Giới thiệu JavaScript',
            'Chương 2: Biến và Kiểu dữ liệu',
            'Chương 3: Hàm và Sự kiện',
        ];

        foreach ($jsChapters as $index => $cTitle) {
            $chapterId = DB::table('chapters')->insertGetId([
                'course_id' => $course3Id,
                'title' => $cTitle,
                'sort_order' => $index + 1,
            ]);

            // Mỗi chương tạo 3 bài học
            for ($i = 1; $i <= 3; $i++) {
                DB::table('lessons')->insert([
                    'chapter_id' => $chapterId,
                    'title' => 'Bài học số ' . $i . ' của ' . $cTitle,
                    'content_type' => 'video',
                    'content_url' => 'https://youtube.com/watch?v=js_tutorial_' . $i,
                    'sort_order' => $i,
                ]);
            }
        }

        // --- 7. TẠO USER HỌC VIÊN MẪU ---
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

            // Cho mỗi học viên mua ngẫu nhiên khóa học từ 11 khóa học
            $allCourses = [$course1Id, $course2Id, $course3Id, $course4Id, $course5Id, $course6Id, $course7Id, $course8Id, $course9Id, $course10Id, $course11Id];
            $coursePrices = [
                $course1Id => 1200000, $course2Id => 850000, $course3Id => 0,
                $course4Id => 950000, $course5Id => 799000, $course6Id => 1100000,
                $course7Id => 1050000, $course8Id => 850000, $course9Id => 599000,
                $course10Id => 750000, $course11Id => 899000
            ];

            $randomCourse = $allCourses[array_rand($allCourses)];

            DB::table('enrollments')->insert([
                'user_id' => $uId,
                'course_id' => $randomCourse,
                'payment_status' => 'paid',
                'price_at_purchase' => $coursePrices[$randomCourse],
                'enrolled_at' => now(),
            ]);
        }
    }
}
