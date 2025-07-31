<?php

use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\postJson;

beforeEach(function () {
    Customer::truncate();
});

it('registers a new customer', function () {
    $payload = [
        'name' => 'Zied Hamzaoui',
        'email' => 'zied@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    postJson('/api/register', $payload)
        ->assertCreated()
        ->assertJson(
            fn(AssertableJson $json) =>
            $json->where('data.name', 'Zied Hamzaoui')
                ->where('data.email', 'zied@example.com')
                ->missing('data.password')
                ->etc()
        );

    expect(Customer::where('email', 'zied@example.com')->exists())->toBeTrue();
});

it('fails to login with invalid credentials', function () {
    Customer::create([
        'name' => 'Test User',
        'email' => 'user@test.com',
        'password' => Hash::make('correct-password'),
    ]);

    $response = postJson('/api/login', [
        'email' => 'user@test.com',
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'error' => 'Invalid credentials',
        ]);
});

it('logins successfully and returns JWT token', function () {
    $customer = Customer::create([
        'name' => 'Test User',
        'email' => 'user@test.com',
        'password' => Hash::make('correct-password'),
    ]);

    $response = postJson('/api/login', [
        'email' => $customer->email,
        'password' => 'correct-password',
    ]);

    $response->assertOk()
        ->assertJsonStructure([
            'message',
            'token',
        ])
        ->assertJson([
            'message' => 'Successful login',
        ]);
});
