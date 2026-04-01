<?php
$baseUrl = 'http://127.0.0.1:8000/api';

echo "Testing Register Endpoint...\n";
$registerData = [
    'nama_lengkap' => 'Test User',
    'email' => 'test@example.com',
    'nisn' => '1234567890',
    'password' => 'password123',
    'password_confirmation' => 'password123'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/register');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($registerData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$registerResponse = curl_exec($ch);
$registerCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Register Response ($registerCode): $registerResponse\n\n";

echo "Testing Login Endpoint...\n";
$loginData = [
    'nisn' => '1234567890',
    'password' => 'password123'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($loginData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$loginResponse = curl_exec($ch);
$loginCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Login Response ($loginCode): $loginResponse\n\n";
