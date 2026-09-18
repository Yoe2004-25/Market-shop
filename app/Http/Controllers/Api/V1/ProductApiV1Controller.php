<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\Products\CreateProductDTO;
use App\DTOs\Products\UpdateProductDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductsRequest;
use App\Http\Requests\UpdateProductsRequest;
use App\Http\Resources\ProudctResource;
use App\Models\Products;
use App\Services\ProductService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductApiV1Controller extends Controller
{

    use AuthorizesRequests ; 
    public function __construct( private ProductService $service) {
        $this->authorizeResource(Products::class, 'product');
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $products = $this->service->paginate(
                $request->get('per_page', 15),
                $request->only(['search', 'category_id', 'brand_id', 'status'])
            );

            return response()->json([
                'message' => 'Products retrieved successfully.',
                'status'  => true,
                'data'    => ProudctResource::collection($products),
                'meta'    => [
                    'current_page' => $products->currentPage(),
                    'last_page'    => $products->lastPage(),
                    'total'        => $products->total(),
                ],
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Products@index: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to retrieve products.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function store(StoreProductsRequest $request): JsonResponse
    {
        try {
            $dto     = CreateProductDTO::fromRequest($request, Auth::id());
            $product = $this->service->create($dto);

            return response()->json([
                'message' => 'Product created successfully.',
                'status'  => true,
                'data'    => new ProudctResource($product),
            ], 201);
        } catch (\Throwable $th) {
            Log::error('Products@store: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to create product.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function show(Products $product): JsonResponse
    {
        $product->load(['category', 'brand', 'images']);

        return response()->json([
            'message' => 'Product retrieved successfully.',
            'status'  => true,
            'data'    => new ProudctResource($product),
        ], 200);
    }

    public function update(UpdateProductsRequest $request, Products $product): JsonResponse
    {
        try {
            $dto     = UpdateProductDTO::fromRequest($request);
            $product = $this->service->update($product, $dto);

            return response()->json([
                'message' => 'Product updated successfully.',
                'status'  => true,
                'data'    => new ProudctResource($product),
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Products@update: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to update product.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy(Products $product): JsonResponse
    {
        try {
            $this->service->delete($product);

            return response()->json([
                'message' => 'Product deleted successfully.',
                'status'  => true,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to delete product.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }
}