<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Service;
use App\Models\Customer;


class ServiceFactory extends Factory
{
    protected $model = Service::class;


    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'customer_id' => Customer::factory(),
        ];
    }
}
