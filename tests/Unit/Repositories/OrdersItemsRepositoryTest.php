<?php

namespace Tests\Unit\Repositories;

use App\Models\Orders;
use App\Models\OrdersItems;
use App\Models\Products;
use App\Models\User;
use App\Repositories\OrdersItemsRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrdersItemsRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private OrdersItemsRepository $repository;
    private User $user;
    private Orders $order;
    private Products $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new OrdersItemsRepository(new OrdersItems());

        $this->user    = User::factory()->create();
        $this->product = Products::factory()->create(['price' => 100]);
        $this->order   = Orders::factory()->create(['user_id' => $this->user->id]);
    }

    /** @test */
    public function it_can_create_an_item(): void
    {
        $item = $this->repository->create([
            'order_id'   => $this->order->id,
            'product_id' => $this->product->id,
            'user_id'    => $this->user->id,
            'quantity'   => 2,
            'price'      => 100,
            'subtotal'   => 200,
            'details'    => 'Test item',
        ]);

        $this->assertInstanceOf(OrdersItems::class, $item);
        $this->assertDatabaseHas('orders_items', ['id' => $item->id]);
    }

    /** @test */
    public function it_can_find(): void
    {
        $item = OrdersItems::factory()->create([
            'order_id'   => $this->order->id,
            'product_id' => $this->product->id,
            'user_id'    => $this->user->id,
        ]);

        $this->assertEquals($item->id, $this->repository->find($item->id)->id);
    }

    /** @test */
    public function it_can_update(): void
    {
        $item = OrdersItems::factory()->create(['quantity' => 1]);

        $updated = $this->repository->update($item, ['quantity' => 5]);

        $this->assertEquals(5, $updated->quantity);
    }

    /** @test */
    public function it_can_soft_delete(): void
    {
        $item = OrdersItems::factory()->create();

        $this->assertTrue($this->repository->delete($item));
        $this->assertSoftDeleted('orders_items', ['id' => $item->id]);
    }

    /** @test */
    public function it_filters_by_user_id(): void
    {
        OrdersItems::factory()->count(3)->create(['user_id' => $this->user->id]);
        OrdersItems::factory()->create(['user_id' => User::factory()->create()->id]);

        $items = $this->repository->all(['user_id' => $this->user->id]);

        $this->assertCount(3, $items);
    }

    /** @test */
    public function it_finds_by_user(): void
    {
        OrdersItems::factory()->count(2)->create(['user_id' => $this->user->id]);

        $items = $this->repository->findByUser($this->user->id);

        $this->assertCount(2, $items);
    }
}