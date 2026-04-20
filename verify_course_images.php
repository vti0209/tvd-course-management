<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "═══════════════════════════════════════════════════════════════════════════════\n";
echo "COURSE IMAGES VERIFICATION REPORT\n";
echo "═══════════════════════════════════════════════════════════════════════════════\n\n";

$courses = \App\Models\Course::all();

foreach($courses as $course) {
    $imagePath = public_path($course->thumbnail);
    $exists = file_exists($imagePath);
    $status = $exists ? '✓ OK' : '✗ MISSING';
    
    echo "Course ID: {$course->id}\n";
    echo "Title: {$course->title}\n";
    echo "Thumbnail: {$course->thumbnail}\n";
    echo "Status: {$status}\n";
    if ($exists) {
        $size = filesize($imagePath);
        echo "File Size: " . formatBytes($size) . "\n";
    }
    echo "\n";
}

echo "═══════════════════════════════════════════════════════════════════════════════\n";
echo "SUMMARY:\n";
echo "Total Courses: " . count($courses) . "\n";
$validImages = $courses->filter(fn($c) => file_exists(public_path($c->thumbnail)))->count();
echo "Valid Images: $validImages\n";
echo "Status: " . ($validImages == count($courses) ? '✓ ALL IMAGES READY' : '✗ SOME IMAGES MISSING') . "\n";
echo "═══════════════════════════════════════════════════════════════════════════════\n";

function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB'];
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    return round($bytes, $precision) . ' ' . $units[$i];
}