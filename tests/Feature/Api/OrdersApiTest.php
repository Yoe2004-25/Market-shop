<?php

namespace Tests\Feature\Api;

use App\Models\Orders;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
class OrdersApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        Storage::fake('public');

        $user = User::factory()->create();
        $user->assignRole('customer');
        Sanctum::actingAs($user);
    }

    /** @test */
    public function it_can_list_orders(): void
    {
        Orders::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/V1/orders');

        $response->assertStatus(200)
            ->assertJson(['status' => true])
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_can_create_an_order(): void
    {
        $payload = [
            'name'           => 'Test Order',
            'status'         => 'pending',
            'payment_status' => 'pending',
            'total'          => 500,
            'shipping_cost'  => 20,
            'tax'            => 10,
            'grand_total'    => 530,
        ];

        $response = $this->postJson('/api/V1/orders', $payload);

        $response->assertStatus(201)
            ->assertJson(['status' => true]);

        $this->assertDatabaseHas('orders', [
            'name'    => 'Test Order',
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function it_validates_required_fields(): void
    {
        $response = $this->postJson('/api/V1/orders', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'total']);
    }

    /** @test */
    public function it_can_show_an_order(): void
    {
        $order = Orders::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson("/api/v1/orders/{$order->id}");

        $response->assertStatus(200)
            ->assertJson(['status' => true, 'data' => ['id' => $order->id]]);
    }

    /** @test */
    public function it_returns_404_if_order_not_found(): void
    {
        $response = $this->getJson('/api/V1/orders/99999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_an_order(): void
    {
        $order = Orders::factory()->create(['user_id' => $this->user->id]);

        $response = $this->putJson("/api/V1/orders/{$order->id}", [
            'status' => 'success',
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => true]);

        $this->assertDatabaseHas('orders', [
            'id'     => $order->id,
            'status' => 'success',
        ]);
    }

    /** @test */
    public function it_can_delete_an_order(): void
    {
        $order = Orders::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("/api/V1/orders/{$order->id}");

        $response->assertStatus(200)
            ->assertJson(['status' => true]);

        $this->assertSoftDeleted('orders', ['id' => $order->id]);
    }

    /** @test */
    public function it_can_get_my_orders(): void
    {
        Orders::factory()->count(2)->create(['user_id' => $this->user->id]);
        Orders::factory()->create(['user_id' => User::factory()->create()->id]);

        $response = $this->getJson('/api/V1/orders/my');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    /** @test */
    public function it_can_update_order_status(): void
    {
        $order = Orders::factory()->create([
            'user_id' => $this->user->id,
            'status'  => 'pending',
        ]);

        $response = $this->patchJson("/api/V1/orders/{$order->id}/status", [
            'status' => 'success',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('orders', [
            'id'     => $order->id,
            'status' => 'success',
        ]);
    }

    /** @test */
    public function v2_returns_meta_data(): void
    {
        Orders::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/V2/orders');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'meta' => ['total', 'version'],
                'data',
            ])
            ->assertJson(['meta' => ['version' => 'V2']]);
    }

    /** @test */
    public function it_returns_401_when_unauthenticated(): void
    {
        $this->app['auth']->forgetGuards();

        $response = $this->getJson('/api/V1/orders');

        $response->assertStatus(401);
    }
}