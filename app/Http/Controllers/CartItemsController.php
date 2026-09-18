<?php

namespace App\Http\Controllers;

use App\DTOs\CartItem\CreateCartItemDTO;
use App\DTOs\CartItem\UpdateCartItemDTO;
use App\Http\Requests\StoreCart_itemsRequest;
use App\Http\Requests\UpdateCart_itemsRequest;
use App\Models\Cart_items;
use App\Services\CartItemService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartItemsController extends Controller
{
    use AuthorizesRequests ;
    public function __construct( private CartItemService $service) 
    {
        $this->authorizeResource(Cart_items::class,'cartitems'); 
    }


    public function index(Request $request): View
    {
        $items = $this->service->getAll($request->only(['cart_id', 'product_id']));

        return view('cart_items.index', compact('items'));
    }

  
    public function create(): View
    {
        return view('cartitems.create');
    }

   
    public function store(StoreCart_itemsRequest $request): RedirectResponse
    {
        $dto = CreateCartItemDTO::fromRequest($request);
        $this->service->create($dto);

        return redirect()
            ->route('cartitems.index')
            ->with('success', 'Item added to cart.');
    }

  
    public function show(string $id): View
    {
        $item = $this->service->getById((int) $id);

        return view('cartitems.show', compact('item'));
    }

   
    public function edit(string $id): View
    {
        $item = $this->service->getById((int) $id);

        return view('cart_items.edit', compact('item'));
    }

  
    public function update(UpdateCart_itemsRequest $request, string $id): RedirectResponse
    {
        $item = $this->service->getById((int) $id);
        $dto  = UpdateCartItemDTO::fromRequest($request);

        $this->service->update($item, $dto);

        return redirect()
            ->route('cartitems.index')
            ->with('success', 'Cart item updated.');
    }

   
    public function destroy(string $id): RedirectResponse
    {
        $item = $this->service->getById((int) $id);
        $this->service->delete($item);

        return redirect()
            ->route('cartitems.index')
            ->with('success', 'Cart item removed.');
    }
}