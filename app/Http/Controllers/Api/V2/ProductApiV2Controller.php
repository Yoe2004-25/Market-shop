<?php

namespace App\Http\Controllers\Api\V2;

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

class ProductApiV2Controller extends Controller
{
    use AuthorizesRequests ; 
    public function __construct( private ProductService $service ) {
        $this->authorizeResource(Products::class, 'product');
    }

  
    public function index(Request $request): JsonResponse
    {
        $products = $this->service->paginate(
            $request->get('per_page', 15),
            $request->only([
                'search', 'category_id', 'brand_id',
                'status', 'min_price', 'max_price',
            ])
        );

        return response()->json([
            'message' => 'Products retrieved successfully (v2).',
            'status'  => true,
            'meta'    => [
                'current_page' => $products->currentPage(),
                'last_page'    => $products->lastPage(),
                'total'        => $products->total(),
                'per_page'     => $products->perPage(),
                'active_count' => Products::active()->count(),
                'version'      => 'v2',
            ],
            'data'=> ProudctResource::collection($products),
        ], 200);
    }

    public function store(StoreProductsRequest $request): JsonResponse
    {
        $dto     = CreateProductDTO::fromRequest($request, Auth::id());
        $product = $this->service->create($dto);

        return response()->json([
            'message' => 'Product created successfully.',
            'status'  => true,
            'data'    => new ProudctResource($product),
        ], 201);
    }

    public function show(Products $product): JsonResponse
    {
        $product->load(['category', 'brand', 'images', 'reviews']);

        return response()->json([
            'message' => 'Product retrieved successfully.',
            'status'  => true,
            'meta'    => [
                'reviews_count' => $product->reviews->count(),
                'avg_rating'    => round($product->reviews->avg('rating') ?? 0, 2),
                'version'       => 'V2',
            ],
            'data' => new ProudctResource($product),
        ], 200);
    }

    public function update(UpdateProductsRequest $request, Products $product): JsonResponse
    {
        $dto     = UpdateProductDTO::fromRequest($request);
        $product = $this->service->update($product, $dto);

        return response()->json([
            'message' => 'Product updated successfully.',
            'status'=> true,
            'data'=> new ProudctResource($product),
        ], 200);
    }

    public function destroy(Products $product): JsonResponse
    {
        $this->service->delete($product);

        return response()->json([
            'message' => 'Product deleted successfully.',
            'status'  => true,
        ], 200);
    }
}