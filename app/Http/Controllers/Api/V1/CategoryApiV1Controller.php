<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\Category\CategoryDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoriesRequest;
use App\Http\Requests\UpdateCategoriesRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryApiV1Controller extends Controller
{
    public function __construct( private CategoryService $service) {}
   
    public function index(Request $request): JsonResponse
    {
        try {
            $categories = $this->service->getAll($request->only(['search', 'status']));

            return response()->json([
                'message' => 'Categories retrieved successfully.',
                'status'  => true,
                'data'    => CategoryResource::collection($categories),
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Categories@index: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to retrieve categories.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function store(StoreCategoriesRequest $request): JsonResponse
    {
        try {
            $dto      = CategoryDTO::fromRequest($request);
            $category = $this->service->create($dto);

            return response()->json([
                'message' => 'Category created successfully.',
                'status'  => true,
                'data'    => new CategoryResource($category),
            ], 201);
        } catch (\Throwable $th) {
            Log::error('Categories@store: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to create category.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    
    public function show(string $id): JsonResponse
    {
        try {
            $category = $this->service->getById((int) $id);

            return response()->json([
                'message' => 'Category retrieved successfully.',
                'status'  => true,
                'data'    => new CategoryResource($category),
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Categories@show: ' . $th->getMessage());

            return response()->json([
                'message' => 'Category not found.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 404);
        }
    }
    public function update(UpdateCategoriesRequest $request, string $id): JsonResponse
    {
        try {
            $dto      = CategoryDTO::fromRequest($request);
            $category = $this->service->update((int) $id, $dto);

            return response()->json([
                'message' => 'Category updated successfully.',
                'status'  => true,
                'data'    => new CategoryResource($category),
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Categories@update: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to update category.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->service->delete((int) $id);

            return response()->json([
                'message' => 'Category deleted successfully.',
                'status'  => true,
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Categories@destroy: ' . $th->getMessage());

            return response()->json([
                'message' => $th->getMessage(),
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }
}