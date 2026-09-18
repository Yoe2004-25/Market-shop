<?php

namespace App\Http\Controllers;

use App\DTOs\Coupons\CreateCouponsDTO;
use App\DTOs\Coupons\UpdateCouponsDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponsRequest;
use App\Http\Requests\UpdateCouponsRequest;
use App\Models\Coupons;
use App\Services\CouponsService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CouponsController extends Controller
{
    use AuthorizesRequests ; 
    public function __construct(private CouponsService $service ) 
    {
        $this->authorizeResource(Coupons::class,'coupons'); 
    }

   
    public function index(Request $request): View
    {
        $coupons = $this->service->getAll($request->only(['search', 'type', 'status']));

        return view('coupons.index', compact('coupons'));
    }

   
    public function create(): View
    {
        return view('coupons.create');
    }

   
    public function store(StoreCouponsRequest $request): RedirectResponse
    {
        $dto = CreateCouponsDTO::fromRequest($request);
        $this->service->create($dto);

        return redirect()
            ->route('coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

   
    public function show(string $id): View
    {
        $coupon = $this->service->getById((int) $id);

        return view('coupons.show', compact('coupon'));
    }

   
    public function edit(string $id): View
    {
        $coupon = $this->service->getById((int) $id);

        return view('coupons.edit', compact('coupon'));
    }

  
    public function update(UpdateCouponsRequest $request, string $id): RedirectResponse
    {
        $coupon = $this->service->getById((int) $id);
        $dto    = UpdateCouponsDTO::fromRequest($request);

        $this->service->update($coupon, $dto);

        return redirect()
            ->route('coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

   
    public function destroy(string $id): RedirectResponse
    {
        $coupon = $this->service->getById((int) $id);
        $this->service->delete($coupon);

        return redirect()
            ->route('coupons.index')
            ->with('success', 'Coupon deleted successfully.');
    }
}