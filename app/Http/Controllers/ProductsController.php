<?php

namespace App\Http\Controllers;

use App\DTOs\Products\CreateProductDTO;
use App\DTOs\Products\UpdateProductDTO;
use App\Http\Requests\StoreProductsRequest;
use App\Http\Requests\UpdateProductsRequest;
use App\Models\Products;
use App\Services\ProductService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProductsController extends Controller
{
    use AuthorizesRequests;
    public function __construct(private ProductService $service ) {
        
        $this->authorizeResource(Products::class, 'products');
    }

   
    public function index(Request $request): View
    {
        $products = $this->service->paginate(
            $request->get('per_page', 10),
            $request->only(['search', 'category_id', 'brand_id', 'status'])
        );

        return view('products.index', compact('products'));
    }

    
    public function create(): View
    {
        return view('products.create');
    }

    public function store(StoreProductsRequest $request): RedirectResponse
    {
        $dto = CreateProductDTO::fromRequest($request, Auth::id());
        $this->service->create($dto);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Products $product): View
    {
        $product->load(['category', 'brand', 'images', 'reviews']);

        return view('products.show', compact('product'));
    }

    /** GET /products/{product}/edit */
    public function edit(Products $product): View
    {
        return view('products.edit', compact('product'));
    }

    
    public function update(UpdateProductsRequest $request, Products $product): RedirectResponse
    {
        $dto = UpdateProductDTO::fromRequest($request);
        $this->service->update($product, $dto);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Products $product): RedirectResponse
    {
        $this->service->delete($product);

        return redirect() ->route('products.index')->with('success', 'Product deleted successfully.');
    }
}