<?php

namespace App\Http\Controllers;

use App\DTOs\Brands\CreateBrandsDTO;
use App\DTOs\Brands\UpdateBrandsDTO;
use App\Http\Requests\StoreBrandsRequest;
use App\Http\Requests\UpdateBrandsRequest;
use App\Models\Brands;
use App\Services\BrandsService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BrandsController extends Controller
{
    use AuthorizesRequests ;
    public function __construct(private BrandsService $service) 
    {
        $this->authorizeResource(Brands::class,'brands');
    }

   
    public function index(Request $request): View
    {
        $brands = $this->service->getAll($request->only(['search']));

        return view('brands.index', compact('brands'));
    }

    /** GET /brands/create → brands/create.blade.php */
    public function create(): View
    {
        return view('brands.create');
    }

    /** POST /brands */
    public function store(StoreBrandsRequest $request): RedirectResponse
    {
        $dto = CreateBrandsDTO::fromRequest($request);
        $this->service->create($dto);

        return redirect()
            ->route('brands.index')
            ->with('success', 'Brand created successfully.');
    }

    /** GET /brands/{id} → brands/show.blade.php */
    public function show(string $id): View
    {
        $brand = $this->service->getById((int) $id);

        return view('brands.show', compact('brand'));
    }

    /** GET /brands/{id}/edit → brands/edit.blade.php */
    public function edit(string $id): View
    {
        $brand = $this->service->getById((int) $id);

        return view('brands.edit', compact('brand'));
    }

    /** PUT /brands/{id} */
    public function update(UpdateBrandsRequest $request, string $id): RedirectResponse
    {
        $brand = $this->service->getById((int) $id);
        $dto   = UpdateBrandsDTO::fromRequest($request);

        $this->service->update($brand, $dto);

        return redirect()
            ->route('brands.index')
            ->with('success', 'Brand updated successfully.');
    }

    /** DELETE /brands/{id} */
    public function destroy(string $id): RedirectResponse
    {
        $brand = $this->service->getById((int) $id);
        $this->service->delete($brand);

        return redirect()
            ->route('brands.index')
            ->with('success', 'Brand deleted successfully.');
    }
}