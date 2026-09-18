<?php

namespace App\Http\Controllers;

use App\Models\Product_images;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProduct_imagesRequest;
use App\Http\Requests\UpdateProduct_imagesRequest;

class ProductImagesController extends Controller
{
    public function index()
    {
        $images = Product_images::with('product')->latest()->paginate(12);
        return view('productimages.index', compact('images'));
    }

    public function create()
    {
        $products = Products::select('id', 'name')->get();
        return view('productimages.create', compact('products'));
    }

    public function store(StoreProduct_imagesRequest $request)
    {
        $data = $request->validated([]);
        $data['image'] = $request->file('image')->store('products', 'public');
        if (!empty($data['primary'])) {
            Product_images::where('product_id', $data['product_id'])->update(['primary' => false]);
        }
        Product_images::create($data);
        return redirect()->route('product-images.index')->with('success', 'Image uploaded successfully.');
    }

    /**
     * GET /product-images/{id}
     */
    public function show(string $id)
    {
        $image = Product_images::with('product')->findOrFail($id);
        return view('productimages.show', compact('image'));
    }

    /**
     * GET /product-images/{id}/edit
     */
    public function edit(string $id)
    {
        $image= Product_images::findOrFail($id);
        $products = Products::select('id', 'name')->get();
        return view('productimages.edit', compact('image', 'products'));
    }
    public function update(UpdateProduct_imagesRequest $request, string $id)
    {
        $image = Product_images::findOrFail($id);

        $data = $request->validate([]);

       
        if ($request->hasFile('image')) {
            // امسح القديمة
            if ($image->image && Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }

            $data['image'] = $request->file('image')->store('products', 'public');
        }

        if(!empty($data['primary'])) 
        {
            Product_images::where('product_id', $data['product_id'] ?? $image->product_id)->where('id', '!=', $image->id)->update(['primary' => false]);
        }

        $image->update($data);

        return redirect()->route('product-images.index')->with('success', 'Image updated successfully.');
    }

   
    public function destroy(string $id)
    {
        $image = Product_images::findOrFail($id);
        if ($image->image && Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }

        $image->delete();

        return redirect()->route('productimages.index')->with('success', 'Image deleted successfully.');
    }
}