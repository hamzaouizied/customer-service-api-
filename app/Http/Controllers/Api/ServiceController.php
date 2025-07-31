<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Http\Requests\Service\CreateServiceCustomerRequest;
use App\Http\Resources\Service\ServiceResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\Service\UpdateServiceRequest;
use App\Http\Resources\Service\ServicesOfCustomer;
use App\Http\Resources\Service\AllServicesResource;
use App\Models\Customer;

/**
 * @OA\Tag(
 *     name="Services",
 *     description="API Endpoints for managing Services"
 * )
 */
class ServiceController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/service/store",
     *     summary="Create a new service for a customer",
     *     tags={"Services"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description", "customer_id"},
     *             @OA\Property(property="name", type="string", example="Internet Plan"),
     *             @OA\Property(property="description", type="string", example="Unlimited 100 Mbps"),
     *             @OA\Property(property="customer_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Service created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ServiceResource")
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(CreateServiceCustomerRequest $request): ServiceResource
    {
        $service = Service::create([
            'name' => $request->name,
            'description' => $request->description,
            'customer_id' => $request->customer_id
        ]);

        return new ServiceResource($service);
    }

    /**
     * @OA\Get(
     *     path="/api/service/customer/{customer}",
     *     summary="Get services of a customer",
     *     tags={"Services"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="customer",
     *         in="path",
     *         required=true,
     *         description="Customer ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of services for a customer",
     *         @OA\JsonContent(ref="#/components/schemas/ServicesOfCustomer")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Customer not found")
     * )
     */
    public function customerServices(Customer $customer): ServicesOfCustomer
    {
        return new ServicesOfCustomer($customer);
    }

    /**
     * @OA\Get(
     *     path="/api/service/all",
     *     summary="Get all services with customers",
     *     tags={"Services"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of all services",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/AllServicesResource")
     *             ),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function getAll(): AnonymousResourceCollection
    {
        $customers = Service::with('customer')->paginate(perPage: 10);

        return AllServicesResource::collection($customers);
    }



    /**
     * @OA\Patch(
     *     path="/api/service/update/{service}",
     *     summary="Update an existing service",
     *     tags={"Services"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="service",
     *         in="path",
     *         required=true,
     *         description="Service ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description"},
     *             @OA\Property(property="name", type="string", example="Updated Plan"),
     *             @OA\Property(property="description", type="string", example="Updated description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Service updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ServiceResource")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Service not found")
     * )
     */
    public function update(Service $service, UpdateServiceRequest $request): ServiceResource
    {
        $service->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return new ServiceResource($service);
    }

    /**
     * @OA\Delete(
     *     path="/api/service/delete/{service}",
     *     summary="Delete a service",
     *     tags={"Services"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="service",
     *         in="path",
     *         required=true,
     *         description="Service ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Service deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Service successfully deleted")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Service not found")
     * )
     */
    public function delete(Service $service): JsonResponse
    {
        $service->delete();
        return response()->json(['message' => 'Service successfully deleted'], 201);
    }
}
