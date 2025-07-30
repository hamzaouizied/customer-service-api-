<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ServicesOfCustomer",
 *     type="object",
 *     title="Services of Customer",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Internet Package"),
 *     @OA\Property(property="description", type="string", example="High speed internet"),
 *     @OA\Property(property="price", type="number", format="float", example=29.99)
 * )
 */
class ServicesOfCustomer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'services' => $this->services,
        ];
    }
}
