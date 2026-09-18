<?php

namespace Tests\Unit\Repositories;

use App\Models\Coupons;
use App\Models\Orders;
use App\Models\User;
use App\Repositories\OrdersRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrdersRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private OrdersRepository $repository;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new OrdersRepository(new Orders());
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_can_create_an_order(): void
    {
        $data = [
            'user_id'        => $this->user->id,
            'name'           => 'Test Order',
            'status'         => 'pending',
            'payment_status' => 'pending',
            'total'          => 500,
            'shipping_cost'  => 20,
            'tax'            => 10,
            'grand_total'    => 530,
        ];

        $order = $this->repository->create($data);

        $this->assertInstanceOf(Orders::class, $order);
        $this->assertDatabaseHas('orders', ['name' => 'Test Order']);
    }

    /** @test */
    public function it_can_find_an_order(): void
    {
        $order = Orders::factory()->create(['user_id' => $this->user->id]);

        $found = $this->repository->find($order->id);

        $this->assertNotNull($found);
        $this->assertEquals($order->id, $found->id);
    }

    /** @test */
    public function it_returns_null_if_order_not_found(): void
    {
        $this->assertNull($this->repository->find(99999));
    }

    /** @test */
    public function it_can_update_an_order(): void
    {
        $order = Orders::factory()->create(['status' => 'pending']);

        $updated = $this->repository->update($order, ['status' => 'success']);

        $this->assertEquals('success', $updated->status);
    }

    /** @test */
    public function it_can_soft_delete_an_order(): void
    {
        $order = Orders::factory()->create();

        $result = $this->repository->delete($order);

        $this->assertTrue($result);
        $this->assertSoftDeleted('orders', ['id' => $order->id]);
    }

    /** @test */
    public function it_can_filter_orders_by_status(): void
    {
        Orders::factory()->create(['status' => 'success', 'user_id' => $this->user->id]);
        Orders::factory()->create(['status' => 'pending', 'user_id' => $this->user->id]);

        $orders = $this->repository->all(['status' => 'success']);

        $this->assertCount(1, $orders);
    }

    /** @test */
    public function it_can_get_orders_by_user(): void
    {
        Orders::factory()->count(3)->create(['user_id' => $this->user->id]);
        Orders::factory()->create(['user_id' => User::factory()->create()->id]);

        $orders = $this->repository->findByUser($this->user->id);

        $this->assertCount(3, $orders);
    }

    /** @test */
    public function it_can_update_status(): void
    {
        $order = Orders::factory()->create(['status' => 'pending']);

        $updated = $this->repository->updateStatus($order, 'failed');

        $this->assertEquals('failed', $updated->status);
    }

    /** @test */
    public function it_can_update_payment_status(): void
    {
        $order = Orders::factory()->create(['payment_status' => 'pending']);

        $updated = $this->repository->updatePaymentStatus($order, 'success');

        $this->assertEquals('success', $updated->payment_status);
    }
}