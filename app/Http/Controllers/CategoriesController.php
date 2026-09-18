<?php

namespace App\Http\Controllers;

use App\DTOs\Category\CategoryDTO;
use App\Http\Requests\StoreCategoriesRequest;
use App\Http\Requests\UpdateCategoriesRequest;
use App\Models\Categories;
use App\Services\CategoryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoriesController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private CategoryService $service)
    {
        $this->authorizeResource(Categories::class,'categories');
    }

    public function index(Request $request): View
    {
        $categories = $this->service->getAll($request->only(['search', 'status']));

        return view('categories.index', compact('categories'));
    }

    
    public function create(): View
    {
        return view('categories.create');
    }

   
    public function store(StoreCategoriesRequest $request): RedirectResponse
    {
        try {
            $dto = CategoryDTO::fromRequest($request);
            $this->service->create($dto);

            return redirect()
                ->route('categories.index')
                ->with('success', 'Category created successfully.');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create category: ' . $th->getMessage());
        }
    }

    
    public function show(string $id): View
    {
        $category = $this->service->getById((int) $id);

        return view('categories.show', compact('category'));
    }

   
    public function edit(string $id): View
    {
        $category = $this->service->getById((int) $id);

        return view('categories.edit', compact('category'));
    }

   
    public function update(UpdateCategoriesRequest $request, string $id): RedirectResponse
    {
        try {
            $dto = CategoryDTO::fromRequest($request);
            $this->service->update((int) $id, $dto);

            return redirect()
                ->route('categories.index')
                ->with('success', 'Category updated successfully.');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update category: ' . $th->getMessage());
        }
    }

   
    public function destroy(string $id): RedirectResponse
    {
        try {
            $this->service->delete((int) $id);

            return redirect()
                ->route('categories.index')
                ->with('success', 'Category deleted successfully.');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }
}