<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$berita = Illuminate\Support\Facades\DB::table('beritas')->select('judul', 'konten')->limit(1)->first();
echo "JUDUL: " . $berita->judul . "\n";
echo "KONTEN: " . $berita->konten . "\n";
