<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\AllCustomersResource;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
