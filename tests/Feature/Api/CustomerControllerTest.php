<?php

use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

beforeEach(function () {
    Customer::truncate();
    $this->user = Customer::factory()->create();
});

it('shows a customer', function () {
    $customer = Customer::factory()->create();
    actingAs($this->user, 'customer-api')
        ->getJson("/api/customer/show/{$customer->id}")
        ->assertOk()
        ->assertJson(
            fn(AssertableJson $json) =>
            $json->where('data.name', $customer->name)
                ->where('data.email', $customer->email)
                ->has('data.created_at')
                ->etc()
        );
});

it('returns 401 if not authenticated when getting all customers', function () {
    getJson('/api/customer/all')->assertUnauthorized();
});

it('gets paginated list of customers', function () {
    Customer::factory()->count(15)->create();

    actingAs($this->user, 'customer-api')
        ->getJson('/api/customer/all')
        ->assertOk()
        ->assertJsonStructure([
            'data',
            'links',
            'meta',
        ])
        ->assertJsonCount(10, 'data');
});

it('updates a customer', function () {
    $customer = Customer::factory()->create([
        'password' => Hash::make('oldpassword'),
    ]);

    $payload = [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
        'password' => 'newpassword',
        'password_confirmation' => 'newpassword',
    ];

    actingAs($this->user, 'customer-api')
        ->patchJson("/api/customer/update/{$customer->id}", $payload)
        ->assertOk()
        ->assertJson(
            fn(AssertableJson $json) =>
            $json->where('data.email', $payload['email'])
                ->where('data.name', $payload['name'])
                ->has('data.created_at')
                ->etc()
        );

    $customer->refresh();
    expect(Hash::check('newpassword', $customer->password))->toBeTrue();
});

it('deletes a customer', function () {
    $customer = Customer::factory()->create();

    actingAs($this->user, 'customer-api')
        ->deleteJson("/api/customer/delete/{$customer->id}")
        ->assertStatus(201)
        ->assertJson(['message' => 'Customer successfully deleted']);

    expect(Customer::find($customer->id))->toBeNull();
});
