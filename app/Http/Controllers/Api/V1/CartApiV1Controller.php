<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\Carts\CreateCartDTO;
use App\DTOs\Carts\UpdateCartDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Http\Resources\CartResource;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartApiV1Controller extends Controller
{
    public function __construct( private CartService $service) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $carts = $this->service->getAll($request->only(['search', 'user_id']));

            return response()->json([
                'message' => 'Carts retrieved successfully.',
                'status'  => true,
                'data'    => CartResource::collection($carts),
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Carts@index: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to retrieve carts.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function store(StoreCartRequest $request): JsonResponse
    {
        try {
            $dto  = CreateCartDTO::fromRequest($request, Auth::id());
            $cart = $this->service->create($dto);

            return response()->json([
                'message'=>'Cart created successfully.',
                'status'=>true,
                'data'=> new CartResource($cart),
            ], 201);
        } catch (\Throwable $th) {
            Log::error('Carts@store: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to create cart.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $cart = $this->service->getById((int) $id);

           
            if ($cart->user_id !== Auth::id()) {
                return response()->json([
                    'message' => 'Unauthorized access.',
                    'status'  => false,
                ], 403);
            }

            return response()->json([
                'message' => 'Cart retrieved successfully.',
                'status'  => true,
                'data'    => new CartResource($cart),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Cart not found.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 404);
        }
    }

    public function update(UpdateCartRequest $request, string $id): JsonResponse
    {
        try {
            $cart = $this->service->getById((int) $id);

            if ($cart->user_id !== Auth::id()) {
                return response()->json([
                    'message' => 'Unauthorized access.',
                    'status'  => false,
                ], 403);
            }

            $dto  = UpdateCartDTO::fromRequest($request);
            $cart = $this->service->update($cart, $dto);

            return response()->json([
                'message' => 'Cart updated successfully.',
                'status'  => true,
                'data'    => new CartResource($cart),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to update cart.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $cart = $this->service->getById((int) $id);

            if ($cart->user_id !== Auth::id()) {
                return response()->json([
                    'message' => 'Unauthorized access.',
                    'status'  => false,
                ], 403);
            }

            $this->service->delete($cart);

            return response()->json([
                'message' => 'Cart deleted successfully.',
                'status'  => true,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to delete cart.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    
    public function myCart(): JsonResponse
    {
        try {
            $cart = $this->service->getOrCreateForUser(Auth::id());

            return response()->json([
                'message' => 'Your cart retrieved successfully.',
                'status'  => true,
                'data'    => new CartResource($cart->load(['items.product'])),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to retrieve your cart.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }
}