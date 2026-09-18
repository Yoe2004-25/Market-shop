<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\OrdersItems\CreateOrdersItemsDTO;
use App\DTOs\OrdersItems\UpdateOrdersItemsDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrdersItemsRequest;
use App\Http\Requests\UpdateOrdersItemsRequest;
use App\Http\Resources\OrdersItemsResource;
use App\Services\OrdersItemsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrdersItemsApiV1Controller extends Controller
{
    public function __construct(
        private OrdersItemsService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $items = $this->service->getAll($request->only([
                'search', 'price', 'quantity', 'user_id',
            ]));

            return response()->json([
                'message' => 'Orders items retrieved successfully.',
                'status'  => true,
                'data'    => OrdersItemsResource::collection($items),
            ], 200);
        } catch (\Throwable $th) {
            Log::error('OrdersItems@index: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to retrieve orders items.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function store(StoreOrdersItemsRequest $request): JsonResponse
    {
        try {
            $dto  = CreateOrdersItemsDTO::fromRequest($request, Auth::id());
            $item = $this->service->create($dto);

            return response()->json([
                'message' => 'Orders item created successfully.',
                'status'  => true,
                'data'    => new OrdersItemsResource($item),
            ], 201);
        } catch (\Throwable $th) {
            Log::error('OrdersItems@store: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to create orders item.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $item = $this->service->getById((int) $id);

            if (Auth::id() !== $item->user_id) {
                return response()->json([
                    'message' => 'Unauthorized access.',
                    'status'  => false,
                ], 403);
            }

            return response()->json([
                'message' => 'Orders item retrieved successfully.',
                'status'  => true,
                'data'    => new OrdersItemsResource($item),
            ], 200);
        } catch (\Throwable $th) {
            Log::error('OrdersItems@show: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to retrieve orders item.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 404);
        }
    }

    public function update(UpdateOrdersItemsRequest $request, string $id): JsonResponse
    {
        try {
            $item = $this->service->getById((int) $id);

            if (Auth::id() !== $item->user_id) {
                return response()->json([
                    'message' => 'Unauthorized access.',
                    'status'  => false,
                ], 403);
            }

            $dto     = UpdateOrdersItemsDTO::fromRequest($request);
            $updated = $this->service->update($item, $dto);

            return response()->json([
                'message' => 'Orders item updated successfully.',
                'status'  => true,
                'data'    => new OrdersItemsResource($updated),
            ], 200);
        } catch (\Throwable $th) {
            Log::error('OrdersItems@update: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to update orders item',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $item = $this->service->getById((int) $id);

            if (Auth::id() !== $item->user_id) {
                return response()->json([
                    'message' => 'Unauthorized access.',
                    'status'  => false,
                ], 403);
            }

            $this->service->delete($item);

            return response()->json([
                'message' => 'Orders item deleted successfully.',
                'status'  => true,
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Orders Items not found: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to delete orders item.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }
}