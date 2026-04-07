<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$imageMapping = [
    'Laravel 11 Fullstack' => 'images/Laravel_11_Fullstack.jpg',
    'ReactJS căn bản' => 'images/ReactJS.jpg',
    'Thiết kế Logo Pro' => 'images/Thiet_ke_logo.jpg',
    'Chạy quảng cáo Facebook' => 'images/khoa-hoc-quang-cao-facebook.jpg',
    'Thuyết trình tự tin' => 'images/khoa-hoc-thuyet-trinh.jpg',
    'Vue.js 3 Advanced' => 'images/Vue.js_3_Advanced.jpg',
    'TypeScript Mastery' => 'images/TypeScript.jpg',
    'UI/UX Design Pro' => 'images/UI_UX_Design_Pro.jpg',
];

$courses = \App\Models\Course::all();
foreach($courses as $course) {
    if (isset($imageMapping[$course->title])) {
        $course->update(['image' => $imageMapping[$course->title]]);
        echo "✓ Updated: " . $course->title . " -> " . $imageMapping[$course->title] . "\n";
    } else {
        echo "- Skipped: " . $course->title . " (no matching image)\n";
    }
}
echo "\nDone!\n";