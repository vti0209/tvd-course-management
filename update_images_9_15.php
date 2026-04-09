<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$imageMapping = [
    9 => 'images/khoa-hoc-thuyet-trinh1.jpg',
    10 => 'images/slide1.jfif',
    11 => 'images/slide2.png',
    12 => 'images/slide3.jfif',
    13 => 'images/slide4.png',
    14 => 'images/ReactJS.jpg',
    15 => 'images/Vue.js_3_Advanced.jpg',
];

foreach($imageMapping as $courseId => $imagePath) {
    $course = \App\Models\Course::find($courseId);
    if ($course) {
        $course->update(['image' => $imagePath]);
        echo "✓ Updated ID " . $courseId . " (" . $course->title . ") -> " . $imagePath . "\n";
    }
}
echo "\nDone!\n";
