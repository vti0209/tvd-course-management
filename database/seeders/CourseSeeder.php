<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('courses')->insert([
            [
                'id' => 1, 'category_id' => 1, 'title' => 'Laravel 11 Fullstack', 'slug' => 'laravel-11-fullstack',
                'description' => null, 'price' => 1200000.00, 'thumbnail' => 'Laravel_11_Fullstack.jpg', 'duration' => '40 giờ', 'status' => 'published', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 2, 'category_id' => 1, 'title' => 'ReactJS căn bản', 'slug' => 'reactjs-can-ban',
                'description' => null, 'price' => 850000.00, 'thumbnail' => 'ReactJS.jpg', 'duration' => '30 giờ', 'status' => 'published', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 3, 'category_id' => 2, 'title' => 'Thiết kế Logo Pro', 'slug' => 'thiet-ke-logo-pro',
                'description' => null, 'price' => 500000.00, 'thumbnail' => 'Thiet_ke_logo.jpg', 'duration' => '20 giờ', 'status' => 'published', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 4, 'category_id' => 3, 'title' => 'Chạy quảng cáo Facebook', 'slug' => 'facebook-ads-master',
                'description' => null, 'price' => 1500000.00, 'thumbnail' => 'khoa-hoc-quang-cao-facebook.jpg', 'duration' => '25 giờ', 'status' => 'published', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 5, 'category_id' => 4, 'title' => 'Thuyết trình tự tin', 'slug' => 'thuyet-trinh-tu-tin',
                'description' => null, 'price' => 300000.00, 'thumbnail' => 'khoa-hoc-thuyet-trinh.jpg', 'duration' => '15 giờ', 'status' => 'published', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 6, 'category_id' => 1, 'title' => 'Vue.js 3 Advanced', 'slug' => 'vue-js-3-advanced',
                'description' => null, 'price' => 950000.00, 'thumbnail' => 'Vue.js_3_Advanced.jpg', 'duration' => '35 giờ', 'status' => 'published', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 7, 'category_id' => 1, 'title' => 'TypeScript Mastery', 'slug' => 'typescript-mastery',
                'description' => null, 'price' => 800000.00, 'thumbnail' => 'TypeScript.jpg', 'duration' => '32 giờ', 'status' => 'published', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 8, 'category_id' => 2, 'title' => 'UI/UX Design Pro', 'slug' => 'ui-ux-design-pro',
                'description' => null, 'price' => 750000.00, 'thumbnail' => 'UI_UX_Design_Pro.jpg', 'duration' => '28 giờ', 'status' => 'published', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 9, 'category_id' => 2, 'title' => 'Figma Complete Course', 'slug' => 'figma-complete-course',
                'description' => null, 'price' => 650000.00, 'thumbnail' => 'UI_UX_Design_Pro.jpg', 'duration' => '24 giờ', 'status' => 'published', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 10, 'category_id' => 3, 'title' => 'Google Ads SEO Master', 'slug' => 'google-ads-seo-master',
                'description' => null, 'price' => 1400000.00, 'thumbnail' => 'khoa-hoc-quang-cao-facebook.jpg', 'duration' => '22 giờ', 'status' => 'published', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 11, 'category_id' => 3, 'title' => 'Email Marketing Expert', 'slug' => 'email-marketing-expert',
                'description' => null, 'price' => 550000.00, 'thumbnail' => 'khoa-hoc-quang-cao-facebook.jpg', 'duration' => '18 giờ', 'status' => 'published', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 12, 'category_id' => 4, 'title' => 'Giao tiếp hiệu quả', 'slug' => 'giao-tiep-hieu-qua',
                'description' => null, 'price' => 400000.00, 'thumbnail' => 'khoa-hoc-thuyet-trinh.jpg', 'duration' => '12 giờ', 'status' => 'published', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 13, 'category_id' => 4, 'title' => 'Lãnh đạo và quản lý', 'slug' => 'lanh-dao-quan-ly',
                'description' => null, 'price' => 700000.00, 'thumbnail' => 'khoa-hoc-thuyet-trinh1.jpg', 'duration' => '26 giờ', 'status' => 'published', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 14, 'category_id' => 1, 'title' => 'Next.js Full Stack', 'slug' => 'nextjs-full-stack',
                'description' => null, 'price' => 1100000.00, 'thumbnail' => 'Laravel_11_Fullstack.jpg', 'duration' => '38 giờ', 'status' => 'published', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'id' => 15, 'category_id' => 1, 'title' => 'Node.js Backend Master', 'slug' => 'nodejs-backend-master',
                'description' => null, 'price' => 1050000.00, 'thumbnail' => 'ReactJS.jpg', 'duration' => '36 giờ', 'status' => 'published', 'created_at' => now(), 'updated_at' => now()
            ],
        ]);
    }
}