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
use App\Models\Customer;

class ServiceController extends Controller
{
    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function customerServices(Customer $customer): ServicesOfCustomer
    {
        return new ServicesOfCustomer($customer);
    }

    public function getAll(): AnonymousResourceCollection
    {
        $customers = Service::with('customers')->paginate(perPage: 10);

        return AllServicesResource::collection($customers);
    }


    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     */
    public function delete(Service $service): JsonResponse
    {
        $service->delete();
        return response()->json(['message' => 'Service successfully deleted'], 201);
    }
}
