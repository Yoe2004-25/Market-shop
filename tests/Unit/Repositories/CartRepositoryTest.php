<?php

namespace Tests\Unit\Repositories;

use App\Models\Cart;
use App\Models\Cart_items;
use App\Models\Products;
use App\Models\User;
use App\Repositories\CartRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private CartRepository $repository;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CartRepository(new Cart());
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_can_create_a_cart(): void
    {
        $cart = $this->repository->create([
            'user_id' => $this->user->id,
            'name'    => 'My Cart',
            'content' => 'test',
        ]);

        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertDatabaseHas('carts', ['name' => 'My Cart']);
    }

    /** @test */
    public function it_can_find_a_cart(): void
    {
        $cart = Cart::factory()->create(['user_id' => $this->user->id]);

        $found = $this->repository->find($cart->id);

        $this->assertNotNull($found);
        $this->assertEquals($cart->id, $found->id);
    }

    /** @test */
    public function it_returns_null_if_cart_not_found(): void
    {
        $this->assertNull($this->repository->find(99999));
    }

    /** @test */
    public function it_can_update_a_cart(): void
    {
        $cart = Cart::factory()->create(['name' => 'Old']);

        $updated = $this->repository->update($cart, ['name' => 'New']);

        $this->assertEquals('New', $updated->name);
    }

    /** @test */
    public function it_can_soft_delete_a_cart(): void
    {
        $cart = Cart::factory()->create();

        $result = $this->repository->delete($cart);

        $this->assertTrue($result);
        $this->assertSoftDeleted('carts', ['id' => $cart->id]);
    }

    /** @test */
    public function it_can_find_cart_by_user(): void
    {
        Cart::factory()->create(['user_id' => $this->user->id]);

        $found = $this->repository->findByUser($this->user->id);

        $this->assertNotNull($found);
        $this->assertEquals($this->user->id, $found->user_id);
    }

    /** @test */
    public function it_creates_cart_if_not_exists_for_user(): void
    {
        $cart = $this->repository->getOrCreateForUser($this->user->id);

        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertDatabaseHas('carts', ['user_id' => $this->user->id]);
    }

    /** @test */
    public function it_returns_existing_cart_for_user(): void
    {
        $existing = Cart::factory()->create(['user_id' => $this->user->id]);

        $cart = $this->repository->getOrCreateForUser($this->user->id);

        $this->assertEquals($existing->id, $cart->id);
    }
}