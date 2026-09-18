<?php

namespace Tests\Unit\Services;

use App\DTOs\OrdersItems\CreateOrdersItemsDTO;
use App\DTOs\OrdersItems\UpdateOrdersItemsDTO;
use App\Models\Orders;
use App\Models\OrdersItems;
use App\Models\Products;
use App\Models\User;
use App\Services\OrdersItemsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class OrdersItemsServiceTest extends TestCase
{
    use RefreshDatabase;

    private OrdersItemsService $service;
    private User $user;
    private Orders $order;
    private Products $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(OrdersItemsService::class);
        $this->user    = User::factory()->create();
        $this->product = Products::factory()->create(['price' => 100]);
        $this->order   = Orders::factory()->create(['user_id' => $this->user->id]);
        Cache::flush();
    }

    /** @test */
    public function it_creates_an_item(): void
    {
        $dto = new CreateOrdersItemsDTO(
            order_id:   $this->order->id,
            product_id: $this->product->id,
            quantity:   2,
            price:      100,
            subtotal:   200,
            details:    'Test',
            user_id:    $this->user->id,
        );

        $item = $this->service->create($dto);

        $this->assertInstanceOf(OrdersItems::class, $item);
    }

    /** @test */
    public function it_updates_an_item(): void
    {
        $item = OrdersItems::factory()->create(['quantity' => 1]);

        $dto = new UpdateOrdersItemsDTO(quantity: 5);

        $this->assertEquals(5, $this->service->update($item, $dto)->quantity);
    }

    /** @test */
    public function it_deletes_an_item(): void
    {
        $item = OrdersItems::factory()->create();

        $this->assertTrue($this->service->delete($item));
        $this->assertSoftDeleted('orders_items', ['id' => $item->id]);
    }

    /** @test */
    public function it_caches_get_all(): void
    {
        OrdersItems::factory()->count(3)->create();

        $first = $this->service->getAll();
        OrdersItems::factory()->create();
        $second = $this->service->getAll();

        $this->assertEquals($first->count(), $second->count());
    }

    /** @test */
    public function it_gets_by_user(): void
    {
        OrdersItems::factory()->count(2)->create(['user_id' => $this->user->id]);

        $items = $this->service->getByUser($this->user->id);

        $this->assertCount(2, $items);
    }
}