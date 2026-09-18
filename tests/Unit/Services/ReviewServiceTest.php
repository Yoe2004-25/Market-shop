<?php

namespace Tests\Unit\Services;

use App\DTOs\Reviews\CreateReviewDTO;
use App\DTOs\Reviews\UpdateReviewDTO;
use App\Models\Products;
use App\Models\Reviews;
use App\Models\User;
use App\Services\ReviewService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ReviewServiceTest extends TestCase
{
    use RefreshDatabase;

    private ReviewService $service;
    private User $user;
    private Products $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ReviewService::class);
        $this->user = User::factory()->create();
        $this->product = Products::factory()->create();
        $this->user->assignRole('customer') ;
        Cache::flush();
    }

    /** @test */
    public function it_creates_a_review(): void
    {
        $dto = new CreateReviewDTO(
            user_id:    $this->user->id,
            product_id: $this->product->id,
            rating:     5,
            comment:    'Great!',
        );

        $review = $this->service->create($dto);

        $this->assertInstanceOf(Reviews::class, $review);
    }

    /** @test */
    public function it_updates(): void
    {
        $review = Reviews::factory()->create(['rating' => 3]);

        $dto = new UpdateReviewDTO(rating: 5);

        $this->assertEquals(5, $this->service->update($review, $dto)->rating);
    }

    /** @test */
    public function it_deletes(): void
    {
        $review = Reviews::factory()->create();

        $this->assertTrue($this->service->delete($review));
        $this->assertSoftDeleted('reviews', ['id' => $review->id]);
    }

    /** @test */
    public function it_gets_average(): void
    {
        Reviews::factory()->create(['product_id' => $this->product->id, 'rating' => 5, 'user_id' => User::factory()->create()->id]);
        Reviews::factory()->create(['product_id' => $this->product->id, 'rating' => 3, 'user_id' => User::factory()->create()->id]);

        $this->assertEquals(4.0, $this->service->getAverageRating($this->product->id));
    }

    /** @test */
    public function it_caches_get_all(): void
    {
        Reviews::factory()->count(3)->create();

        $first = $this->service->getAll();
        Reviews::factory()->create();
        $second = $this->service->getAll();

        $this->assertEquals($first->count(), $second->count());
    }
}