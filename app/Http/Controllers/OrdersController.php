<?php

namespace App\Http\Controllers;

use App\DTOs\Orders\CreateOrdersDTO;
use App\DTOs\Orders\UpdateOrdersDTO;
use App\Http\Requests\StoreOrdersRequest;
use App\Http\Requests\UpdateOrdersRequest;
use App\Models\Orders;
use App\Services\OrdersService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrdersController extends Controller
{
    use AuthorizesRequests ; 
    public function __construct(private OrdersService $service) {
        $this->authorizeResource(Orders::class,'orders'); 
    }

   
    public function index(Request $request): View
    {
        $orders = $this->service->getAll($request->only([
            'search', 'status', 'payment_status',
        ]));

        return view('orders.index', compact('orders'));
    }

 
    public function create(): View
    {
        return view('orders.create');
    }

  
    public function store(StoreOrdersRequest $request): RedirectResponse
    {
        $dto = CreateOrdersDTO::fromRequest($request, Auth::id());
        $this->service->create($dto);

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order created successfully.');
    }

   
    public function show(string $id): View
    {
        $order = $this->service->getById((int) $id);

        return view('orders.show', compact('order'));
    }

    
    public function edit(string $id): View
    {
        $order = $this->service->getById((int) $id);

        return view('orders.edit', compact('order'));
    }

   
    public function update(UpdateOrdersRequest $request, string $id): RedirectResponse
    {
        $order = $this->service->getById((int) $id);
        $dto   = UpdateOrdersDTO::fromRequest($request);

        $this->service->update($order, $dto);

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order updated successfully.');
    }

    
    public function destroy(string $id): RedirectResponse
    {
        $order = $this->service->getById((int) $id);
        $this->service->delete($order);

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}