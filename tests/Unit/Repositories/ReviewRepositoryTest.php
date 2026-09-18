<?php

namespace Tests\Unit\Repositories;

use App\Models\Products;
use App\Models\Reviews;
use App\Models\User;
use App\Repositories\ReviewRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ReviewRepository $repository;
    private User $user;
    private Products $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ReviewRepository(new Reviews());
        $this->user = User::factory()->create();
        $this->user->assignRole('customer');
        $this->product = Products::factory()->create();
    }

    /** @test */
    public function it_can_create(): void
    {
        $review = $this->repository->create([
            'user_id'    => $this->user->id,
            'product_id' => $this->product->id,
            'rating'     => 5,
            'comment'    => 'Great!',
        ]);

        $this->assertInstanceOf(Reviews::class, $review);
        $this->assertDatabaseHas('reviews', ['rating' => 5]);
    }

    /** @test */
    public function it_can_find(): void
    {
        $review = Reviews::factory()->create([
            'user_id'    => $this->user->id,
            'product_id' => $this->product->id,
        ]);

        $this->assertEquals($review->id, $this->repository->find($review->id)->id);
    }

    /** @test */
    public function it_can_update(): void
    {
        $review = Reviews::factory()->create(['rating' => 3]);

        $updated = $this->repository->update($review, ['rating' => 5]);

        $this->assertEquals(5, $updated->rating);
    }

    /** @test */
    public function it_can_soft_delete(): void
    {
        $review = Reviews::factory()->create();

        $this->assertTrue($this->repository->delete($review));
        $this->assertSoftDeleted('reviews', ['id' => $review->id]);
    }

    /** @test */
    public function it_can_get_by_product(): void
    {
        Reviews::factory()->count(3)->create(['product_id' => $this->product->id]);

        $reviews = $this->repository->getByProduct($this->product->id);

        $this->assertCount(3, $reviews);
    }

    /** @test */
    public function it_calculates_average(): void
    {
        Reviews::factory()->create(['product_id' => $this->product->id, 'rating' => 5, 'user_id' => User::factory()->create()->id]);
        Reviews::factory()->create(['product_id' => $this->product->id, 'rating' => 3, 'user_id' => User::factory()->create()->id]);

        $avg = $this->repository->averageRatingForProduct($this->product->id);

        $this->assertEquals(4.0, $avg);
    }
}