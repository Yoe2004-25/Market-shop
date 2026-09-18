<?php

namespace Tests\Unit\Repositories;

use App\Models\Products;
use App\Models\User;
use App\Models\wishlist;
use App\Repositories\WishlistRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private WishlistRepository $repository;
    private User $user;
    private Products $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new WishlistRepository(new wishlist());
        $this->user = User::factory()->create();
        $this->product = Products::factory()->create();
    }

    /** @test */
    public function it_can_create_a_wishlist_item(): void
    {
        $item = $this->repository->create([
            'user_id'    => $this->user->id,
            'product_id' => $this->product->id,
        ]);

        $this->assertInstanceOf(wishlist::class, $item);
        $this->assertDatabaseHas('wishlists', [
            'user_id'    => $this->user->id,
            'product_id' => $this->product->id,
        ]);
    }

    /** @test */
    public function it_can_find_by_user_and_product(): void
    {
        wishlist::factory()->create([
            'user_id'=>$this->user->id,
            'product_id'=>$this->product->id,
        ]);

        $found = $this->repository->findByUserAndProduct($this->user->id, $this->product->id);

        $this->assertNotNull($found);
    }

    /** @test */
    public function it_returns_null_if_not_found(): void
    {
        $found = $this->repository->findByUserAndProduct($this->user->id, 99999);

        $this->assertNull($found);
    }

    /** @test */
    public function it_can_get_by_user(): void
    {
        wishlist::factory()->count(3)->create(['user_id' => $this->user->id]);
        wishlist::factory()->create(['user_id' => User::factory()->create()->id]);

        $items = $this->repository->getByUser($this->user->id);

        $this->assertCount(3, $items);
    }

    /** @test */
    public function it_can_soft_delete(): void
    {
        $item = wishlist::factory()->create();

        $this->assertTrue($this->repository->delete($item));
        $this->assertSoftDeleted('wishlists', ['id' => $item->id]);
    }

    /** @test */
    public function it_can_clear_user_wishlist(): void
    {
        wishlist::factory()->count(3)->create(['user_id' => $this->user->id]);

        $deleted = $this->repository->clearUserWishlist($this->user->id);

        $this->assertEquals(3, $deleted);
    }
}