<?php

namespace App\Http\Controllers;

use App\DTOs\Wishlists\CreateWishlistDTO;
use App\DTOs\Wishlists\UpdateWishlistDTO;
use App\Http\Requests\StoreWishlistRequest;
use App\Http\Requests\UpdateWishlistRequest;
use App\Models\wishlist;
use App\Services\WishlistService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WishlistController extends Controller
{
    use AuthorizesRequests ; 
    public function __construct( private WishlistService $service) {
        $this->authorizeResource(wishlist::class, 'wishlist');
    }

    public function index(Request $request): View
    {
        $wishlists = $this->service->getByUser(Auth::id());

        return view('wishlist.index', compact('wishlists'));
    }

   
    public function create(): View
    {
        return view('wishlist.create');
    }

    public function store(StoreWishlistRequest $request): RedirectResponse
    {
        $dto = CreateWishlistDTO::fromRequest($request, Auth::id());
        $this->service->create($dto);

        return redirect()
            ->route('wishlist.index')
            ->with('success', 'Product added to your wishlist.');
    }

    public function show(wishlist $wishlist): View
    {
        $wishlist->load(['product', 'user']);

        return view('wishlist.show', compact('wishlist'));
    }

    public function edit(wishlist $wishlist): View
    {
        return view('wishlist.edit', compact('wishlist'));
    }

    /** PUT /wishlist/{wishlist} */
    public function update(UpdateWishlistRequest $request, wishlist $wishlist): RedirectResponse
    {
        $dto = UpdateWishlistDTO::fromRequest($request);
        $this->service->update($wishlist, $dto);

        return redirect()
            ->route('wishlist.index')
            ->with('success', 'Wishlist updated.');
    }

    public function destroy(wishlist $wishlist): RedirectResponse
    {
        $this->service->delete($wishlist);

        return redirect()
            ->route('wishlist.index')
            ->with('success', 'Product removed from wishlist.');
    }
}