<?php

namespace Tests\Feature\Api;

use App\Models\Coupons;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;


class CouponsApiTest extends TestCase
{
    use RefreshDatabase;

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
    public function it_can_list_coupons(): void
    {
        Coupons::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/coupons');

        $response->assertStatus(200)
            ->assertJson(['status' => true])
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_can_create_a_coupon(): void
    {
        $payload = [
            'name'        => 'Test Coupon',
            'code'        => 'NEW20',
            'type'        => 'percentage',
            'value'       => 20,
            'expire_date' => now()->addMonth()->toDateTimeString(),
            'usage_limit' => 10,
            'status'      => 'active',
        ];

        $response = $this->postJson('/api/v1/coupons', $payload);

        $response->assertStatus(201)
            ->assertJson(['status' => true]);

        $this->assertDatabaseHas('coupons', ['code' => 'NEW20']);
    }

    /** @test */
    public function it_validates_required_fields(): void
    {
        $response = $this->postJson('/api/v1/coupons', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'code', 'type', 'value']);
    }

    /** @test */
    public function it_rejects_duplicate_code(): void
    {
        Coupons::factory()->create(['code' => 'DUP']);

        $response = $this->postJson('/api/v1/coupons', [
            'name'  => 'Dup Test',
            'code'  => 'DUP',
            'type'  => 'fixed',
            'value' => 10,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['code']);
    }

    /** @test */
    public function it_rejects_past_expiry_date(): void
    {
        $response = $this->postJson('/api/v1/coupons', [
            'name'        => 'Past',
            'code'        => 'PAST01',
            'type'        => 'fixed',
            'value'       => 10,
            'expire_date' => now()->subDay()->toDateTimeString(),
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['expire_date']);
    }

    /** @test */
    public function it_can_show_a_coupon(): void
    {
        $coupon = Coupons::factory()->create();

        $response = $this->getJson("/api/v1/coupons/{$coupon->id}");

        $response->assertStatus(200)
            ->assertJson(['status' => true, 'data' => ['id' => $coupon->id]]);
    }

    /** @test */
    public function it_can_update_a_coupon(): void
    {
        $coupon = Coupons::factory()->create(['value' => 10]);

        $response = $this->putJson("/api/v1/coupons/{$coupon->id}", [
            'value' => 30,
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => true]);

        $this->assertDatabaseHas('coupons', ['id' => $coupon->id, 'value' => 30]);
    }

    /** @test */
    public function it_can_delete_a_coupon(): void
    {
        $coupon = Coupons::factory()->create();

        $response = $this->deleteJson("/api/v1/coupons/{$coupon->id}");

        $response->assertStatus(200)
            ->assertJson(['status' => true]);

        $this->assertSoftDeleted('coupons', ['id' => $coupon->id]);
    }

    /** @test */
    public function it_can_validate_a_coupon(): void
    {
        Coupons::factory()->create([
            'code'        => 'VALID10',
            'type'        => 'percentage',
            'value'       => 10,
            'status'      => 'active',
            'expire_date' => now()->addDay(),
        ]);

        $response = $this->postJson('/api/v1/coupons/validate', [
            'code'  => 'VALID10',
            'total' => 500,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status'   => true,
                'discount' => 50,
            ]);
    }

    /** @test */
    public function it_returns_401_when_unauthenticated(): void
    {
        $this->app['auth']->forgetGuards();

        $response = $this->getJson('/api/v1/coupons');

        $response->assertStatus(401);
    }
}