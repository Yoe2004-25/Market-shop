<?php

namespace App\Http\Controllers\Api\V1;

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

class CouponsApiV1Controller extends Controller
{
    public function __construct(
        private CouponsService $service
    ) {}

    /**
     * GET /api/v1/coupons
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $coupons = $this->service->getAll($request->only([
                'search', 'type', 'status',
            ]));

            return response()->json([
                'message' => 'Coupons retrieved successfully.',
                'status'  => true,
                'data'    => CouponsResource::collection($coupons),
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Coupons@index: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to retrieve coupons.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/v1/coupons
     */
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
            Log::error('Coupons@store: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to create coupon.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/v1/coupons/{id}
     */
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
            Log::error('Coupons@show: ' . $th->getMessage());

            return response()->json([
                'message' => 'Coupon not found.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 404);
        }
    }

    /**
     * PUT /api/v1/coupons/{id}
     */
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
            Log::error('Coupons@update: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to update coupon.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * DELETE /api/v1/coupons/{id}
     */
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
            Log::error('Coupons@destroy: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to delete coupon.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/v1/coupons/validate
     * التحقق من صلاحية كوبون وحساب الخصم.
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

    /**
     * GET /api/v1/coupons/active
     */
    public function active(): JsonResponse
    {
        try {
            $coupons = $this->service->getActive();

            return response()->json([
                'message' => 'Active coupons retrieved successfully.',
                'status'  => true,
                'data'    => CouponsResource::collection($coupons),
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Coupons@active: ' . $th->getMessage());

            return response()->json([
                'message' => 'Failed to retrieve active coupons.',
                'status'  => false,
                'error'   => $th->getMessage(),
            ], 500);
        }
    }
}