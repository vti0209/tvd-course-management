<?php
require_once 'bootstrap/app.php';

$app = require_once 'bootstrap/app.php';

$users = \App\Models\User::all(['id', 'username', 'email', 'role', 'status']);
echo "Total users: " . count($users) . "\n";
echo str_repeat("=", 80) . "\n";

foreach ($users as $u) {
    echo sprintf("ID: %2d | Email: %-25s | Role: %-8s | Status: %-7s\n", 
        $u->id, $u->email, $u->role, $u->status);
}
