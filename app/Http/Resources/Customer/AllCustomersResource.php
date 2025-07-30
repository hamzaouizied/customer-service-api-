<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="AllCustomersResource",
 *     type="object",
 *     title="AllCustomersResource",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Hamzaoui Zied"),
 *     @OA\Property(property="email", type="string", format="email", example="hamzaoui@test.com"),
 *     @OA\Property(
 *         property="services",
 *         type="array",
 *         @OA\Items(type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="Premium Plan")
 *         )
 *     )
 * )
 **/
class AllCustomersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'services' => $this->services
        ];
    }
}
