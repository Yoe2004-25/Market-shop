<?php

namespace App\Http\Controllers;

use App\DTOs\OrdersItems\CreateOrdersItemsDTO;
use App\DTOs\OrdersItems\UpdateOrdersItemsDTO;
use App\Http\Requests\StoreOrdersItemsRequest;
use App\Http\Requests\UpdateOrdersItemsRequest;
use App\Models\OrdersItems;
use App\Services\OrdersItemsService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrdersItemsController extends Controller
{
    use AuthorizesRequests ; 
    public function __construct( private OrdersItemsService $service) 
    {
            $this->authorizeResource('ordersitems',OrdersItems::class);    
    }


    public function index(Request $request): View
    {
        $items = $this->service->getAll($request->only([
            'search', 'price', 'quantity', 'user_id',
        ]));

        return view('orderitems.index', compact('items'));
    }

    public function create(): View
    {
        return view('orderitems.create');
    }

    public function store(StoreOrdersItemsRequest $request): RedirectResponse
    {
        try {
            $dto = CreateOrdersItemsDTO::fromRequest($request, Auth::id());
            $this->service->create($dto);

            return redirect()
                ->route('orderitems.index')
                ->with('success', 'Orders item created successfully.');
        } catch (\Throwable $th) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create item: ' . $th->getMessage());
        }
    }

   
    public function show(string $id): View
    {
        $item = $this->service->getById((int) $id);

        return view('orderitems.show', compact('item'));
    }

    
    public function edit(string $id): View
    {
        $item = $this->service->getById((int) $id);

        return view('orderitems.edit', compact('item'));
    }

    /**
     * PUT /orders-items/{id}
     */
    public function update(UpdateOrdersItemsRequest $request, string $id): RedirectResponse
    {
        try {
            $item = $this->service->getById((int) $id);
            $dto  = UpdateOrdersItemsDTO::fromRequest($request);

            $this->service->update($item, $dto);

            return redirect()->route('orderitems.index') ->with('success', 'Orders item updated successfully.');
        } catch (\Throwable $th) {
            return back()->withInput()->with('error', 'Failed to update item: ' . $th->getMessage());
        }
    }

    /**
     * DELETE /orders-items/{id}
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            $item = $this->service->getById((int) $id);
            $this->service->delete($item);

            return redirect() ->route('orderitems.index')->with('success', 'Orders item deleted successfully.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Failed to delete item: ' . $th->getMessage());
        }
    }
}