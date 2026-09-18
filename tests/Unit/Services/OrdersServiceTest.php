<?php

namespace Tests\Unit\Services;

use App\DTOs\Orders\CreateOrdersDTO;
use App\DTOs\Orders\UpdateOrdersDTO;
use App\Models\Orders;
use App\Models\User;
use App\Services\OrdersService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class OrdersServiceTest extends TestCase
{
    use RefreshDatabase;

    private OrdersService $service;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(OrdersService::class);
        $this->user = User::factory()->create();
        Cache::flush();
    }

    /** @test */
    public function it_creates_an_order(): void
    {
        $dto = new CreateOrdersDTO(
            user_id:        $this->user->id,
            coupon_id:      null,
            name:           'Test Order',
            status:         'pending',
            payment_status: 'pending',
            total:          500,
            shipping_cost:  20,
            tax:            10,
            grand_total:    530,
        );

        $order = $this->service->create($dto);

        $this->assertInstanceOf(Orders::class, $order);
        $this->assertEquals('Test Order', $order->name);
    }

    /** @test */
    public function it_updates_an_order(): void
    {
        $order = Orders::factory()->create(['status' => 'pending']);

        $dto = new UpdateOrdersDTO(status: 'success');

        $updated = $this->service->update($order, $dto);

        $this->assertEquals('success', $updated->status);
    }

    /** @test */
    public function it_deletes_an_order(): void
    {
        $order = Orders::factory()->create();

        $result = $this->service->delete($order);

        $this->assertTrue($result);
        $this->assertSoftDeleted('orders', ['id' => $order->id]);
    }

    /** @test */
    public function it_caches_get_all_result(): void
    {
        Orders::factory()->count(3)->create();

        $first = $this->service->getAll();

        Orders::factory()->create();

        $second = $this->service->getAll();

        $this->assertEquals($first->count(), $second->count());
    }

    /** @test */
    public function it_clears_cache_on_create(): void
    {
        $this->service->getAll();

        $dto = new CreateOrdersDTO(
            user_id:        $this->user->id,
            coupon_id:      null,
            name:           'Cache Test',
            status:         'pending',
            payment_status: 'pending',
            total:          100,
            shipping_cost:  0,
            tax:            0,
            grand_total:    100,
        );

        $this->service->create($dto);

        $this->assertCount(1, $this->service->getAll());
    }

    /** @test */
    public function it_updates_status(): void
    {
        $order = Orders::factory()->create(['status' => 'pending']);

        $updated = $this->service->updateStatus($order, 'success');

        $this->assertEquals('success', $updated->status);
    }
}