<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\CartItem\CreateCartItemDTO;
use App\DTOs\CartItem\UpdateCartItemDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCart_itemsRequest;
use App\Http\Requests\UpdateCart_itemsRequest;
use App\Http\Resources\Cart_itemsResource;
use App\Services\CartItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartItemsApiV1Controller extends Controller
{
    public function __construct(
        private CartItemService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $items = $this->service->getAll($request->only(['cart_id', 'product_id']));

            return response()->json([
                'message' => 'Cart items retrieved successfully.',
                'status'  => true,
                'data'    => Cart_itemsResource::collection($items),
            ], 200);
        } catch (\Throwable $th) {
            Log::error('CartItems@index: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to retrieve cart items.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function store(StoreCart_itemsRequest $request): JsonResponse
    {
        try {
            $dto  = CreateCartItemDTO::fromRequest($request);
            $item = $this->service->create($dto);

            return response()->json([
                'message' => 'Item added to cart.',
                'status'  => true,
                'data'    => new Cart_itemsResource($item->load(['cart', 'product'])),
            ], 201);
        } catch (\Throwable $th) {
            Log::error('CartItems@store: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to add item.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $item = $this->service->getById((int) $id);

            return response()->json([
                'message' => 'Cart item retrieved successfully.',
                'status'  => true,
                'data'    => new Cart_itemsResource($item),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message'=> 'Cart item not found.',
                'status'=> false,
                'error'=> $th->getMessage(),
            ], 404);
        }
    }

    public function update(UpdateCart_itemsRequest $request, string $id): JsonResponse
    {
        try {
            $item = $this->service->getById((int) $id);
            $dto  = UpdateCartItemDTO::fromRequest($request);
            $item = $this->service->update($item, $dto);

            return response()->json([
                'message'=>'Cart item updated successfully.',
                'status'=> true,
                'data' => new Cart_itemsResource($item),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message'=> 'Failed to update cart item.',
                'status' => false,
                'error'=> $th->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $item = $this->service->getById((int) $id);
            $this->service->delete($item);

            return response()->json([
                'message' => 'Cart item deleted successfully.',
                'status'  => true,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message'=> 'Failed to delete cart item.',
                'status'=> false,
                'error'=> $th->getMessage(),
            ], 500);
        }
    }

   
    public function byCart(int $cartId): JsonResponse
    {
        $items = $this->service->getByCart($cartId);

        return response()->json([
            'message'=>'Cart items retrieved.',
            'status'=> true,
            'data'=> Cart_itemsResource::collection($items),
        ]);
    }

   
    public function clearCart(int $cartId): JsonResponse
    {
        $deleted = $this->service->clearCart($cartId);

        return response()->json([
            'message' => "Cart cleared. {$deleted} items deleted.",
            'status'  => true,
        ]);
    }
}