<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Customer;
use App\Http\Resources\CustomerResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CustomerLoginRequest;
use Illuminate\Http\JsonResponse;

class CustomerAuthController extends Controller
{
    public function register(CustomerRequest $request): CustomerResource
    {
        $customer = new Customer();
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->password = Hash::make($request->password);
        $customer->save();

        $token = JWTAuth::fromUser($customer);

        return new CustomerResource(resource: $customer);
    }

    public function login(CustomerLoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('customer-api')->attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'token' => $token,
        ]);
    }

    public function logout(): JsonResponse
    {
        auth('customer-api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
