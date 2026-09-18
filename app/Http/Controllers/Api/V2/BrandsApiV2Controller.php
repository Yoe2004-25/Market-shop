<?php

namespace App\Http\Controllers\Api\V2;

use App\DTOs\Brands\CreateBrandsDTO;
use App\DTOs\Brands\UpdateBrandsDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandsRequest;
use App\Http\Requests\UpdateBrandsRequest;
use App\Http\Resources\BrandResource;
use App\Services\BrandsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BrandsApiV2Controller extends Controller
{
    public function __construct(
        private BrandsService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $brands = $this->service->getAll($request->only([
                'search', 'sort_by', 'per_page',
            ]));

            return response()->json([
                'message' => 'Brands retrieved successfully.',
                'status'  => true,
                'data'    => BrandResource::collection($brands),
                'meta'    => [
                    'total' => $brands->count(),
                ],
            ], 200);
        } catch (\Throwable $th) {
            Log::error('BrandsV2@index: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to retrieve brands.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function store(StoreBrandsRequest $request): JsonResponse
    {
        try {
            $dto   = CreateBrandsDTO::fromRequest($request);
            $brand = $this->service->create($dto);

            return response()->json([
                'message' => 'Brand created successfully.',
                'status'  => true,
                'data'    => new BrandResource($brand),
            ], 201);
        } catch (\Throwable $th) {
            Log::error('BrandsV2@store: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to create brand.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $brand = $this->service->getById((int) $id);

            return response()->json([
                'message' => 'Brand retrieved successfully.',
                'status'  => true,
                'data'    => new BrandResource($brand),
            ], 200);
        } catch (\Throwable $th) {
            Log::error('BrandsV2@show: ' . $th->getMessage());

            return response()->json([
                'message' => 'Brand not found.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 404);
        }
    }

    public function update(UpdateBrandsRequest $request, string $id): JsonResponse
    {
        try {
            $brand = $this->service->getById((int) $id);
            $dto   = UpdateBrandsDTO::fromRequest($request);
            $brand = $this->service->update($brand, $dto);

            return response()->json([
                'message' => 'Brand updated successfully.',
                'status'  => true,
                'data'    => new BrandResource($brand),
            ], 200);
        } catch (\Throwable $th) {
            Log::error('BrandsV2@update: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to update brand.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $brand = $this->service->getById((int) $id);
            $this->service->delete($brand);

            return response()->json([
                'message' => 'Brand deleted successfully.',
                'status'  => true,
            ], 200);
        } catch (\Throwable $th) {
            Log::error('BrandsV2@destroy: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to delete brand.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }
}