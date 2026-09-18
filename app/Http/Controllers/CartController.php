<?php

namespace App\Http\Controllers;

use App\DTOs\Carts\CreateCartDTO;
use App\DTOs\Carts\UpdateCartDTO;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
    use AuthorizesRequests; 
    public function __construct( private CartService $service ) 
    {
        $this->authorizeResource(Cart::class,'carts');    
    }

   
    public function index(Request $request): View
    {
        $carts = $this->service->getAll($request->only(['search', 'user_id']));

        return view('carts.index', compact('carts'));
    }

    
    public function create(): View
    {
        return view('carts.create');
    }

    /** POST /carts */
    public function store(StoreCartRequest $request): RedirectResponse
    {
        $dto = CreateCartDTO::fromRequest($request, Auth::id());
        $this->service->create($dto);

        return redirect()
            ->route('carts.index')
            ->with('success', 'Cart created successfully.');
    }

  
    public function show(string $id): View
    {
        $cart = $this->service->getById((int) $id);

        return view('carts.show', compact('cart'));
    }

    /** GET /carts/{id}/edit → carts/edit.blade.php */
    public function edit(string $id): View
    {
        $cart = $this->service->getById((int) $id);

        return view('carts.edit', compact('cart'));
    }

    /** PUT /carts/{id} */
    public function update(UpdateCartRequest $request, string $id): RedirectResponse
    {
        $cart = $this->service->getById((int) $id);
        $dto  = UpdateCartDTO::fromRequest($request);

        $this->service->update($cart, $dto);

        return redirect()
            ->route('carts.index')
            ->with('success', 'Cart updated successfully.');
    }

    /** DELETE /carts/{id} */
    public function destroy(string $id): RedirectResponse
    {
        $cart = $this->service->getById((int) $id);
        $this->service->delete($cart);

        return redirect()
            ->route('carts.index')
            ->with('success', 'Cart deleted successfully.');
    }
}