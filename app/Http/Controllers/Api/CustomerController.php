<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Http\Resources\Customer\CustomerResource;
use App\Http\Resources\Customer\AllCustomersResource;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @OA\Tag(
 *     name="Customers",
 *     description="API Endpoints for Customers"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 * )
 */
class CustomerController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/customer/show/{customer}",
     *     summary="View a customer",
     *     tags={"Customers"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="customer",
     *         in="path",
     *         description="ID of customer to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CustomerResource")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Customer not found")
     * )
     */
    public function show(Customer $customer): CustomerResource
    {
        return new CustomerResource($customer);
    }


    /**
     * @OA\Get(
     *     path="/api/customer/all",
     *     summary="View all customers",
     *     tags={"Customers"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of customers",
     *         @OA\JsonContent(type="object",
     *           @OA\Property(property="data", type="array",
     *             @OA\Items(ref="#/components/schemas/AllCustomersResource")
     *           ),
     *           @OA\Property(property="links", type="object"),
     *           @OA\Property(property="meta", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function getAll(): AnonymousResourceCollection
    {
        $customers = Customer::with('services')->paginate(perPage: 10);

        return AllCustomersResource::collection($customers);
    }

    /**
     * @OA\Patch(
     *     path="/api/customer/update/{customer}",
     *     summary="Update a customer",
     *     tags={"Customers"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="customer",
     *         in="path",
     *         description="ID of customer to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="Hamzaoui Zied"),
     *             @OA\Property(property="email", type="string", format="email", example="hamzaoui@test.com"),
     *             @OA\Property(property="password", type="string", format="password", example="zied123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="zied123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CustomerResource")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=404, description="Customer not found")
     * )
     */
    public function update(Customer $customer, UpdateCustomerRequest $request): CustomerResource
    {
        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return new CustomerResource($customer);
    }
    /**
     * @OA\Delete(
     *     path="/api/customer/delete/{customer}",
     *     summary="Delete a customer",
     *     tags={"Customers"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="customer",
     *         in="path",
     *         description="ID of customer to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer successfully deleted",
     *         @OA\JsonContent(
     *           @OA\Property(property="message", type="string", example="Customer successfully deleted")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Customer not found")
     * )
     */

    public function delete(Customer $customer): JsonResponse
    {
        $customer->delete();
        return response()->json(['message' => 'Customer successfully deleted'], 201);
    }
}
