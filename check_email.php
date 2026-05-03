<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = Illuminate\Support\Facades\DB::table('users')->where('email', 'calelaa@gmail.com')->first();
$admin = Illuminate\Support\Facades\DB::table('admins')->where('email', 'calelaa@gmail.com')->first();

echo "User: " . ($user ? "Found" : "Not Found") . "\n";
echo "Admin: " . ($admin ? "Found" : "Not Found") . "\n";
