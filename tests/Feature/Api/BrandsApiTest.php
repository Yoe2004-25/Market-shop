<?php

namespace Tests\Feature\Api;

use App\Events\BrandCreated;
use App\Models\Brands;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BrandsApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        Storage::fake('public');

        $user = User::factory()->create();
        $user->assignRole('admin');
        Sanctum::actingAs($user);
    }

    /** @test */
    public function it_can_list_brands(): void
    {
        Brands::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/brands');

        $response->assertStatus(200)
            ->assertJson(['status' => true])
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_can_create_a_brand(): void
    {
        Event::fake();

        $response = $this->postJson('/api/v1/brands', [
            'name' => 'Test Brand',
        ]);

        $response->assertStatus(201)
            ->assertJson(['status' => true]);

        $this->assertDatabaseHas('brands', ['name' => 'Test Brand']);
    }

    /** @test */
    public function it_can_create_a_brand_with_logo(): void
    {
        $file = UploadedFile::fake()->image('brand.png');

        $response = $this->postJson('/api/v1/brands', [
            'name' => 'Brand With Logo',
            'logo' => $file,
        ]);

        $response->assertStatus(201);

        $brand = Brands::where('name', 'Brand With Logo')->first();
        $this->assertNotNull($brand->logo);
        $this->assertTrue(Storage::disk('public')->exists($brand->logo));
    }

    /** @test */
    public function it_dispatches_brand_created_event_on_create(): void
    {
        Event::fake();

        $this->postJson('/api/v1/brands', ['name' => 'Event Brand']);

        Event::assertDispatched(BrandCreated::class);
    }

    /** @test */
    public function it_validates_required_fields(): void
    {
        $response = $this->postJson('/api/v1/brands', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function it_validates_unique_name(): void
    {
        Brands::factory()->create(['name' => 'Unique']);

        $response = $this->postJson('/api/v1/brands', ['name' => 'Unique']);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function it_rejects_non_image_logo(): void
    {
        $file = UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf');

        $response = $this->postJson('/api/v1/brands', [
            'name' => 'Test',
            'logo' => $file,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['logo']);
    }

    /** @test */
    public function it_rejects_logo_larger_than_2mb(): void
    {
        $file = UploadedFile::fake()->image('big.png')->size(3 * 1024); // 3MB

        $response = $this->postJson('/api/v1/brands', [
            'name' => 'Test',
            'logo' => $file,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['logo']);
    }

    /** @test */
    public function it_can_show_a_brand(): void
    {
        $brand = Brands::factory()->create();

        $response = $this->getJson("/api/v1/brands/{$brand->id}");

        $response->assertStatus(200)
            ->assertJson(['status' => true, 'data' => ['id' => $brand->id]]);
    }

    /** @test */
    public function it_returns_404_when_brand_not_found(): void
    {
        $response = $this->getJson('/api/v1/brands/99999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_a_brand(): void
    {
        $brand = Brands::factory()->create(['name' => 'Old Name']);

        $response = $this->putJson("/api/v1/brands/{$brand->id}", [
            'name' => 'New Name',
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => true]);

        $this->assertDatabaseHas('brands', [
            'id'   => $brand->id,
            'name' => 'New Name',
        ]);
    }

    /** @test */
    public function it_can_delete_a_brand(): void
    {
        $brand = Brands::factory()->create();

        $response = $this->deleteJson("/api/v1/brands/{$brand->id}");

        $response->assertStatus(200)
            ->assertJson(['status' => true]);

        $this->assertSoftDeleted('brands', ['id' => $brand->id]);
    }

    /** @test */
    public function it_returns_401_when_unauthenticated(): void
    {
        $this->app['auth']->forgetGuards();

        $response = $this->getJson('/api/v1/brands');

        $response->assertStatus(401);
    }

    /** @test */
    public function v2_endpoint_works_too(): void
    {
        Brands::factory()->count(2)->create();

        $response = $this->getJson('/api/v2/brands');

        $response->assertStatus(200)
            ->assertJson(['status' => true])
            ->assertJsonCount(2, 'data');
    }
}