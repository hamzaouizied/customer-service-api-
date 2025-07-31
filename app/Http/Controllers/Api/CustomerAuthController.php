<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Http\Resources\Customer\CustomerResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Customer\CustomerLoginRequest;
use App\Http\Requests\Customer\CreateCustomerRequest;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *     title="Customer API",
 *     version="1.0.0",
 *     description="API documentation for customer registration and authentication"
 * )
 */
class CustomerAuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     operationId="registerCustomer",
     *     tags={"Customer Auth"},
     *     summary="Register a new customer",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="Hamzaoui Zied"),
     *             @OA\Property(property="email", type="string", format="email", example="zied@test.com"),
     *             @OA\Property(property="password", type="string", format="password", example="zied123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="zied123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Customer registered successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CustomerResource")
     *     )
     * )
     */
    public function register(CreateCustomerRequest $request): CustomerResource
    {
        $customer = new Customer();
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->password = Hash::make($request->password);
        $customer->save();

        return new CustomerResource(resource: $customer);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     operationId="loginCustomer",
     *     tags={"Customer Auth"},
     *     summary="Login a customer and get JWT token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="zied@test.com"),
     *             @OA\Property(property="password", type="string", format="password", example="zied123"),
     * 
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Successful login"),
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid credentials")
     *         )
     *     )
     * )
     */
    public function login(CustomerLoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('customer-api')->attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'message' => 'Successful login',
            'token' => $token,
        ], 200);
    }
}
