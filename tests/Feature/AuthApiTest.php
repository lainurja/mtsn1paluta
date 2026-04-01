<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_and_login()
    {
        $response = $this->postJson('/api/register', [
            'nama_lengkap' => 'Test User',
            'email' => 'test@example.com',
            'nisn' => '1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['token', 'user']);

        $loginResponse = $this->postJson('/api/login', [
            'nisn' => '1234567890',
            'password' => 'password123'
        ]);

        $loginResponse->assertStatus(200)
                     ->assertJsonStructure(['token', 'user']);
    }
}
