<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\Orders\CreateOrdersDTO;
use App\DTOs\Orders\UpdateOrdersDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrdersRequest;
use App\Http\Requests\UpdateOrdersRequest;
use App\Http\Resources\OrdersResource;
use App\Services\OrdersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrdersApiV1Controller extends Controller
{
    public function __construct(
        private OrdersService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $orders = $this->service->getAll($request->only([
                'search', 'status', 'payment_status',
            ]));

            return response()->json([
                'message' => 'Orders retrieved successfully.',
                'status'  => true,
                'data'    => OrdersResource::collection($orders),
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Orders@index: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to retrieve orders.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function store(StoreOrdersRequest $request): JsonResponse
    {
        try {
            $dto   = CreateOrdersDTO::fromRequest($request, Auth::id());
            $order = $this->service->create($dto);

            return response()->json([
                'message' => 'Order created successfully.',
                'status'  => true,
                'data'    => new OrdersResource($order->load(['user', 'items', 'payment'])),
            ], 201);
        } catch (\Throwable $th) {
            Log::error('Orders@store: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to create order.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $order = $this->service->getById((int) $id);

            return response()->json([
                'message' => 'Order retrieved successfully.',
                'status'  => true,
                'data'    => new OrdersResource($order),
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Orders@show: ' . $th->getMessage());

            return response()->json([
                'message' => 'Order not found.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 404);
        }
    }

    public function update(UpdateOrdersRequest $request, string $id): JsonResponse
    {
        try {
            $order = $this->service->getById((int) $id);
            $dto   = UpdateOrdersDTO::fromRequest($request);
            $order = $this->service->update($order, $dto);

            return response()->json([
                'message' => 'Order updated successfully.',
                'status'  => true,
                'data'    => new OrdersResource($order->load(['user', 'items', 'payment'])),
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Orders@update: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to update order.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $order = $this->service->getById((int) $id);
            $this->service->delete($order);

            return response()->json([
                'message' => 'Order deleted successfully.',
                'status'  => true,
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Orders@destroy: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to delete order.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    
    public function updateStatus(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'status' => ['required', 'in:pending,success,failed'],
        ]);

        $order = $this->service->getById((int) $id);
        $order = $this->service->updateStatus($order, $request->status);

        return response()->json([
            'message' => 'Order status updated.',
            'status'  => true,
            'data'    => new OrdersResource($order),
        ]);
    }

    
    public function myOrders(): JsonResponse
    {
        $orders = $this->service->getByUser(Auth::id());

        return response()->json([
            'message' => 'Your orders retrieved successfully.',
            'status'  => true,
            'data'    => OrdersResource::collection($orders),
        ]);
    }
}