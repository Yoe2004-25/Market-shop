<?php

namespace App\Http\Controllers\Api\V2;

use App\DTOs\Coupons\CreateCouponsDTO;
use App\DTOs\Coupons\UpdateCouponsDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponsRequest;
use App\Http\Requests\UpdateCouponsRequest;
use App\Http\Resources\CouponsResource;
use App\Services\CouponsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CouponsApiV2Controller extends Controller
{
    public function __construct(
        private CouponsService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $coupons = $this->service->getAll($request->only([
                'search', 'type', 'status',
            ]));

            // V2: إضافة meta
            return response()->json([
                'message' => 'Coupons retrieved successfully.',
                'status'  => true,
                'meta'    => [
                    'total'        => $coupons->count(),
                    'active_count' => $coupons->where('status', 'active')->count(),
                    'version'      => 'v2',
                ],
                'data'    => CouponsResource::collection($coupons),
            ], 200);
        } catch (\Throwable $th) {
            Log::error('CouponsV2@index: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to retrieve coupons.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function store(StoreCouponsRequest $request): JsonResponse
    {
        try {
            $dto    = CreateCouponsDTO::fromRequest($request);
            $coupon = $this->service->create($dto);

            return response()->json([
                'message' => 'Coupon created successfully.',
                'status'  => true,
                'data'    => new CouponsResource($coupon),
            ], 201);
        } catch (\Throwable $th) {
            Log::error('CouponsV2@store: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to create coupon.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $coupon = $this->service->getById((int) $id);

            return response()->json([
                'message' => 'Coupon retrieved successfully.',
                'status'  => true,
                'data'    => new CouponsResource($coupon),
            ], 200);
        } catch (\Throwable $th) {
            Log::error('CouponsV2@show: ' . $th->getMessage());

            return response()->json([
                'message' => 'Coupon not found.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 404);
        }
    }

    public function update(UpdateCouponsRequest $request, string $id): JsonResponse
    {
        try {
            $coupon = $this->service->getById((int) $id);
            $dto    = UpdateCouponsDTO::fromRequest($request);
            $coupon = $this->service->update($coupon, $dto);

            return response()->json([
                'message' => 'Coupon updated successfully.',
                'status'  => true,
                'data'    => new CouponsResource($coupon),
            ], 200);
        } catch (\Throwable $th) {
            Log::error('CouponsV2@update: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to update coupon.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $coupon = $this->service->getById((int) $id);
            $this->service->delete($coupon);

            return response()->json([
                'message' => 'Coupon deleted successfully.',
                'status'  => true,
            ], 200);
        } catch (\Throwable $th) {
            Log::error('CouponsV2@destroy: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to delete coupon.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/v2/coupons/validate
     */
    public function validateCoupon(Request $request): JsonResponse
    {
        $request->validate([
            'code'  => ['required', 'string'],
            'total' => ['required', 'numeric', 'min:0'],
        ]);

        $coupon = $this->service->getByCode($request->code);

        if (!$coupon) {
            return response()->json([
                'status'   => false,
                'message'  => 'Coupon not found.',
                'discount' => 0,
            ], 404);
        }

        if (!$coupon->isValid()) {
            return response()->json([
                'status'   => false,
                'message'  => 'Coupon is expired or inactive.',
                'discount' => 0,
            ], 422);
        }

        return response()->json([
            'status'   => true,
            'message'  => 'Coupon is valid.',
            'discount' => $coupon->calculateDiscount((float) $request->total),
            'data'     => new CouponsResource($coupon),
        ], 200);
    }
}