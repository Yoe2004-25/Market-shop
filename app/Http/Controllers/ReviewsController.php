<?php

namespace App\Http\Controllers;

use App\DTOs\Reviews\CreateReviewDTO;
use App\DTOs\Reviews\UpdateReviewDTO;
use App\Http\Requests\StoreReviewsRequest;
use App\Http\Requests\UpdateReviewsRequest;
use App\Models\Reviews;
use App\Services\ReviewService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReviewsController extends Controller
{
    use AuthorizesRequests ; 
    public function __construct( private ReviewService $service) {
        $this->authorizeResource(Reviews::class, 'review');
    }

    /** GET /reviews */
    public function index(Request $request): View
    {
        $reviews = $this->service->getAll($request->only([
            'search', 'rating', 'product_id',
        ]));

        return view('reviews.index', compact('reviews'));
    }

    public function create(): View
    {
        return view('reviews.create');
    }

    /** POST /reviews */
    public function store(StoreReviewsRequest $request): RedirectResponse
    {
        $dto = CreateReviewDTO::fromRequest($request, Auth::id());
        $this->service->create($dto);

        return redirect()
            ->route('reviews.index')
            ->with('success', 'Review created successfully.');
    }

 
    public function show(Reviews $review): View
    {
        $review->load(['product', 'user']);

        return view('reviews.show', compact('review'));
    }

    public function edit(Reviews $review): View
    {
        return view('reviews.edit', compact('review'));
    }

   
    public function update(UpdateReviewsRequest $request, Reviews $review): RedirectResponse
    {
        $dto = UpdateReviewDTO::fromRequest($request);
        $this->service->update($review, $dto);

        return redirect()
            ->route('reviews.index')
            ->with('success', 'Review updated successfully.');
    }

    public function destroy(Reviews $review): RedirectResponse
    {
        $this->service->delete($review);

        return redirect()
            ->route('reviews.index')
            ->with('success', 'Review deleted successfully.');
    }
}