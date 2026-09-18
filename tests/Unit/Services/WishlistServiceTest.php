<?php

namespace Tests\Unit\Services;

use App\DTOs\Wishlists\CreateWishlistDTO;
use App\Models\Products;
use App\Models\User;
use App\Models\wishlist;
use App\Services\WishlistService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class WishlistServiceTest extends TestCase
{
    use RefreshDatabase;

    private WishlistService $service;
    private User $user;
    private Products $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(WishlistService::class);
        $this->user = User::factory()->create();
        $this->product = Products::factory()->create();
        Cache::flush();
    }

    /** @test */
    public function it_creates_an_item(): void
    {
        $dto = new CreateWishlistDTO(
            user_id:    $this->user->id,
            product_id: $this->product->id,
        );

        $item = $this->service->create($dto);

        $this->assertInstanceOf(wishlist::class, $item);
    }

    /** @test */
    public function it_returns_existing_if_already_in_wishlist(): void
    {
        $existing = wishlist::factory()->create([
            'user_id'    => $this->user->id,
            'product_id' => $this->product->id,
        ]);

        $dto = new CreateWishlistDTO($this->user->id, $this->product->id);

        $item = $this->service->create($dto);

        $this->assertEquals($existing->id, $item->id);
        $this->assertEquals(1, wishlist::count());
    }

    /** @test */
    public function it_checks_if_in_wishlist(): void
    {
        wishlist::factory()->create([
            'user_id'    => $this->user->id,
            'product_id' => $this->product->id,
        ]);

        $this->assertTrue($this->service->isInWishlist($this->user->id, $this->product->id));
        $this->assertFalse($this->service->isInWishlist($this->user->id, 99999));
    }

    /** @test */
    public function it_removes_by_user_and_product(): void
    {
        wishlist::factory()->create([
            'user_id'    => $this->user->id,
            'product_id' => $this->product->id,
        ]);

        $result = $this->service->removeByUserAndProduct($this->user->id, $this->product->id);

        $this->assertTrue($result);
        $this->assertEquals(0, wishlist::count());
    }

    /** @test */
    public function it_can_delete(): void
    {
        $item = wishlist::factory()->create();

        $this->assertTrue($this->service->delete($item));
        $this->assertSoftDeleted('wishlists', ['id' => $item->id]);
    }
}