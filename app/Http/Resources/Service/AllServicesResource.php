<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="AllServicesResource",
 *     type="object",
 *     title="AllServicesResource",
 *     @OA\Property(property="name", type="string", example="Hosting Plan"),
 *     @OA\Property(property="description", type="string", example="Basic web hosting"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-29T10:30:00Z")
 * )
 */
class AllServicesResource extends JsonResource
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
            'description' => $this->description,
            'created_at' => $this->created_at,
        ];
    }
}