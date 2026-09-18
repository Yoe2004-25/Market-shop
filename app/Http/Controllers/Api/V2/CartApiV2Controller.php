<?php

namespace App\Http\Controllers\Api\V2;

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

class CartApiV2Controller extends Controller
{
    public function __construct(
        private CartService $service
    ) {}

   
    public function index(Request $request): JsonResponse
    {
        $carts = $this->service->getAll($request->only(['search', 'user_id']));

        return response()->json([
            'message'=>'Carts retrieved successfully (v2).',
            'status'=>true,
            'meta'=> [
                'total'=> $carts->count(),
                'total_value'=> $carts->sum(fn ($c) => $c->total),
                'version'=>'v2',
            ],
            'data'=>CartResource::collection($carts),
        ], 200);
    }

    public function store(StoreCartRequest $request): JsonResponse
    {
        $dto  = CreateCartDTO::fromRequest($request, Auth::id());
        $cart = $this->service->create($dto);

        return response()->json([
            'message' => 'Cart created successfully.',
            'status'  => true,
            'data'    => new CartResource($cart),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
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
    }

    public function update(UpdateCartRequest $request, string $id): JsonResponse
    {
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
    }

    public function destroy(string $id): JsonResponse
    {
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
    }
}