<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Image mapping for the 3 courses
$imageMapping = [
    'Lập trình Laravel từ cơ bản đến nâng cao (Dự án thực tế)' => 'images/courses/1776656022_Copy of PN - Round Logo.png',
    'Thiết kế UI/UX hiện đại với Figma' => 'images/courses/1776605790_download.png',
    'JavaScript Cơ bản - Khóa học miễn phí' => 'images/courses/1776604250_download (1).png',
];

echo "Fixing course images...\n";
echo str_repeat("=", 80) . "\n";

$courses = \App\Models\Course::all();
foreach($courses as $course) {
    if (isset($imageMapping[$course->title])) {
        $oldImage = $course->thumbnail;
        $course->update(['thumbnail' => $imageMapping[$course->title]]);
        echo "✓ Updated: " . $course->title . "\n";
        echo "  Old: $oldImage\n";
        echo "  New: " . $imageMapping[$course->title] . "\n\n";
    }
}

echo str_repeat("=", 80) . "\n";
echo "Course images fixed successfully!\n";

// Display all courses with their images
echo "\nAll Courses:\n";
echo str_repeat("-", 80) . "\n";
$courses = \App\Models\Course::all();
foreach($courses as $c) {
    echo "• " . $c->title . "\n";
    echo "  Image: " . ($c->thumbnail ?: 'NULL') . "\n\n";
}