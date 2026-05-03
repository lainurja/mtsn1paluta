<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$users = Illuminate\Support\Facades\DB::table('users')->select('email')->limit(5)->get();
$admins = Illuminate\Support\Facades\DB::table('admins')->select('email')->limit(5)->get();

echo "Users: \n";
print_r($users);
echo "Admins: \n";
print_r($admins);
