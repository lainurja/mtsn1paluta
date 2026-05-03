<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;

try {
    Mail::raw('Ini adalah email uji coba dari sistem PPDB.', function ($message) {
        $message->to('calelaa@gmail.com')
                ->subject('Uji Coba Pengiriman Email');
    });
    echo "Email berhasil dikirim (menurut Laravel).\n";
} catch (\Exception $e) {
    echo "Gagal mengirim email: " . $e->getMessage() . "\n";
}
