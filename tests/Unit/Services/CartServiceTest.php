<?php

namespace Tests\Unit\Services;

use App\DTOs\Carts\CreateCartDTO;
use App\DTOs\Carts\UpdateCartDTO;
use App\Models\Cart;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    private CartService $service;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CartService::class);
        $this->user = User::factory()->create();
        Cache::flush();
    }

    /** @test */
    public function it_creates_a_cart(): void
    {
        $dto = new CreateCartDTO(
            user_id: $this->user->id,
            name:    'Test Cart',
            content: 'Some content',
        );

        $cart = $this->service->create($dto);

        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertEquals('Test Cart', $cart->name);
    }

    /** @test */
    public function it_updates_a_cart(): void
    {
        $cart = Cart::factory()->create(['name' => 'Old']);

        $dto = new UpdateCartDTO(name: 'New');

        $updated = $this->service->update($cart, $dto);

        $this->assertEquals('New', $updated->name);
    }

    /** @test */
    public function it_deletes_a_cart(): void
    {
        $cart = Cart::factory()->create();

        $result = $this->service->delete($cart);

        $this->assertTrue($result);
        $this->assertSoftDeleted('carts', ['id' => $cart->id]);
    }

    /** @test */
    public function it_caches_get_all(): void
    {
        Cart::factory()->count(3)->create();

        $first = $this->service->getAll();
        Cart::factory()->create();
        $second = $this->service->getAll();

        $this->assertEquals($first->count(), $second->count());
    }

    /** @test */
    public function it_gets_cart_by_user(): void
    {
        Cart::factory()->create(['user_id' => $this->user->id]);

        $cart = $this->service->getByUser($this->user->id);

        $this->assertNotNull($cart);
    }

    /** @test */
    public function it_creates_cart_if_not_exists(): void
    {
        $cart = $this->service->getOrCreateForUser($this->user->id);

        $this->assertInstanceOf(Cart::class, $cart);
    }
}