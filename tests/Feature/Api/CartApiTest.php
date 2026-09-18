<?php

namespace Tests\Feature\Api;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
class CartApiTest extends TestCase
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
    public function it_can_list_carts(): void
    {
        Cart::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/V1/carts');

        $response->assertStatus(200)
            ->assertJson(['status' => true])
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_can_create_a_cart(): void
    {
        $response = $this->postJson('/api/v1/carts', [
            'name'    => 'Test Cart',
            'content' => 'Some content',
        ]);

        $response->assertStatus(201)
            ->assertJson(['status' => true]);

        $this->assertDatabaseHas('carts', [
            'name'    => 'Test Cart',
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function it_validates_required_name(): void
    {
        $response = $this->postJson('/api/V1/carts', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function it_can_show_a_cart(): void
    {
        $cart = Cart::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson("/api/v1/carts/{$cart->id}");

        $response->assertStatus(200)
            ->assertJson(['status' => true, 'data' => ['id' => $cart->id]]);
    }

    /** @test */
    public function it_returns_403_for_another_users_cart(): void
    {
        $otherCart = Cart::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);

        $response = $this->getJson("/api/V1/carts/{$otherCart->id}");

        $response->assertStatus(403);
    }

    /** @test */
    public function it_can_update_a_cart(): void
    {
        $cart = Cart::factory()->create(['user_id' => $this->user->id]);

        $response = $this->putJson("/api/V1/carts/{$cart->id}", [
            'name' => 'Updated',
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => true]);

        $this->assertDatabaseHas('carts', [
            'id'   => $cart->id,
            'name' => 'Updated',
        ]);
    }

    /** @test */
    public function it_can_delete_a_cart(): void
    {
        $cart = Cart::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("/api/V1/carts/{$cart->id}");

        $response->assertStatus(200)
            ->assertJson(['status' => true]);

        $this->assertSoftDeleted('carts', ['id' => $cart->id]);
    }

    /** @test */
    public function it_can_get_my_cart(): void
    {
        $response = $this->getJson('/api/V1/carts/my');

        $response->assertStatus(200)
            ->assertJson(['status' => true]);

        $this->assertDatabaseHas('carts', ['user_id' => $this->user->id]);
    }

    /** @test */
    public function v2_returns_meta(): void
    {
        Cart::factory()->count(2)->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/V2/carts');

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'meta', 'data'])
            ->assertJson(['meta' => ['version' => 'V2']]);
    }

    /** @test */
    public function it_returns_401_when_unauthenticated(): void
    {
        $this->app['auth']->forgetGuards();

        $response = $this->getJson('/api/V1/carts');

        $response->assertStatus(401);
    }
}