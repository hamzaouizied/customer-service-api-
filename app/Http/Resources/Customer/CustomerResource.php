<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CustomerResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Hamzaoui Zied"),
 *     @OA\Property(property="email", type="string", format="email", example="zied@test.com"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-30T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-30T10:00:00Z")
 * )
 */
class CustomerResource extends JsonResource
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
        ];
    }
}
