<?php

namespace Tests\Feature\Api;

use App\Models\Orders;
use App\Models\OrdersItems;
use App\Models\Products;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrdersItemsApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Orders $order;
    private Products $product;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        $this->user    = User::factory()->create();
        $this->product = Products::factory()->create(['price' => 100]);
        $this->order   = Orders::factory()->create(['user_id' => $this->user->id]);
        Sanctum::actingAs($this->user);
    }

    /** @test */
    public function it_lists_items(): void
    {
        OrdersItems::factory()->count(3)->create([
            'order_id'   => $this->order->id,
            'product_id' => $this->product->id,
            'user_id'    => $this->user->id,
        ]);

        $response = $this->getJson('/api/V1/ordersitems');

        $response->assertStatus(200)
            ->assertJson(['status' => true])
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_creates_an_item(): void
    {
        $response = $this->postJson('/api/V1/ordersitems', [
            'order_id'   => $this->order->id,
            'product_id' => $this->product->id,
            'quantity'   => 2,
            'price'      => 100,
            'subtotal'   => 200,
            'details'    => 'Test',
        ]);

        $response->assertStatus(201)
            ->assertJson(['status' => true]);

        $this->assertDatabaseHas('ordersitems', [
            'order_id'   => $this->order->id,
            'product_id' => $this->product->id,
        ]);
    }

    /** @test */
    public function it_validates_required_fields(): void
    {
        $response = $this->postJson('/api/V1/ordersitems', []);

        $response->assertStatus(422);
    }

    /** @test */
    public function it_shows_an_item(): void
    {
        $item = OrdersItems::factory()->create([
            'order_id'   => $this->order->id,
            'product_id' => $this->product->id,
            'user_id'    => $this->user->id,
        ]);

        $response = $this->getJson("/api/V1/ordersitems/{$item->id}");

        $response->assertStatus(200)
            ->assertJson(['data' => ['id' => $item->id]]);
    }

    /** @test */
    public function it_forbids_other_users_items(): void
    {
        $otherUser = User::factory()->create();
        $item = OrdersItems::factory()->create([
            'user_id'    => $otherUser->id,
            'order_id'   => Orders::factory()->create(['user_id' => $otherUser->id])->id,
            'product_id' => $this->product->id,
        ]);

        $response = $this->getJson("/api/V1/ordersitems/{$item->id}");

        $response->assertStatus(403);
    }

    /** @test */
    public function it_updates_an_item(): void
    {
        $item = OrdersItems::factory()->create([
            'order_id'   => $this->order->id,
            'product_id' => $this->product->id,
            'user_id'    => $this->user->id,
            'quantity'   => 1,
        ]);

        $response = $this->putJson("/api/V1/ordersitems/{$item->id}", [
            'quantity' => 5,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('ordersitems', [
            'id'       => $item->id,
            'quantity' => 5,
        ]);
    }

    /** @test */
    public function it_deletes_an_item(): void
    {
        $item = OrdersItems::factory()->create([
            'order_id'   => $this->order->id,
            'product_id' => $this->product->id,
            'user_id'    => $this->user->id,
        ]);

        $response = $this->deleteJson("/api/V1/ordersitems/{$item->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('orders_items', ['id' => $item->id]);
    }

    /** @test */
    public function it_returns_401_when_unauthenticated(): void
    {
        $this->app['auth']->forgetGuards();

        $response = $this->getJson('/api/v1/ordersitems');

        $response->assertStatus(401);
    }
}