<?php

use App\Models\Service;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;


uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = Customer::factory()->create();
});

it('creates a new service', function () {
    $customer = Customer::factory()->create();

    $payload = [
        'name' => 'Internet Plan',
        'description' => 'Unlimited 100 Mbps',
        'customer_id' => $customer->id,
    ];

    actingAs($this->user, 'customer-api')
        ->postJson('/api/service/store', $payload)
        ->assertCreated()
        ->assertJson(
            fn($json) =>
            $json->where('data.name', $payload['name'])
                ->where('data.description', $payload['description'])
                ->where('data.customer_id', $customer->id)
                ->etc()
        );

    $this->assertDatabaseHas('services', [
        'name' => $payload['name'],
        'customer_id' => $customer->id,
    ]);
});

it('retrieves services of a customer', function () {
    $customer = Customer::factory()->create();
    Service::factory()->count(3)->create(['customer_id' => $customer->id]);

    actingAs($this->user, 'customer-api')
        ->getJson("/api/service/customer/{$customer->id}")
        ->assertOk()
        ->assertJsonStructure(['data']);
});

it('gets paginated list of all services', function () {
    $user = Customer::factory()->create();

    Service::factory()->count(15)->create([
        'customer_id' => $user->id,
    ]);

    actingAs($user, 'customer-api')
        ->getJson('/api/service/all')
        ->assertOk()
        ->assertJsonStructure([
            'data',
            'links',
            'meta',
        ])
        ->assertJsonCount(10, 'data');
});

it('updates an existing service', function () {
    $service = Service::factory()->create();

    $payload = [
        'name' => 'Updated Plan',
        'description' => 'Updated description',
    ];

    actingAs($this->user, 'customer-api')
        ->patchJson("/api/service/update/{$service->id}", $payload)
        ->assertOk()
        ->assertJson(
            fn($json) =>
            $json->where('data.name', $payload['name'])
                ->where('data.description', $payload['description'])
                ->etc()
        );

    $service->refresh();
    expect($service->name)->toBe($payload['name']);
    expect($service->description)->toBe($payload['description']);
});

it('deletes a service', function () {
    $service = Service::factory()->create();

    actingAs($this->user, 'customer-api')
        ->deleteJson("/api/service/delete/{$service->id}")
        ->assertStatus(201)
        ->assertJson([
            'message' => 'Service successfully deleted',
        ]);

    $this->assertDatabaseMissing('services', ['id' => $service->id]);
});
