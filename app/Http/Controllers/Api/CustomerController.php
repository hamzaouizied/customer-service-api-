<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\AllCustomersResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function show(Customer $customer): CustomerResource
    {
        return new CustomerResource($customer);
    }

    public function getAll(): AnonymousResourceCollection
    {
        $customers = Customer::with('services')->paginate(perPage: 1);

        return AllCustomersResource::collection($customers);
    }

    public function update(Customer $customer, UpdateCustomerRequest $request): CustomerResource
    {
        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return new CustomerResource($customer);
    }


    public function delete(Customer $customer): JsonResponse
    {
        $customer->delete();
        return response()->json(['message' => 'Customer successfully deleted'], 201);
    }
}
