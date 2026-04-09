<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$courses = \App\Models\Course::select('id', 'title', 'image')->get();
echo "Courses in database:\n";
echo str_repeat("-", 80) . "\n";
foreach($courses as $c) {
    echo "ID: " . $c->id . " | Title: " . $c->title . " | Image: " . ($c->image ?: 'NULL') . "\n";
}
echo str_repeat("-", 80) . "\n";
echo "Total: " . count($courses) . " courses\n";
