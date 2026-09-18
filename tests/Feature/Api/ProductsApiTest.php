<?php

namespace Tests\Feature\Api;

use App\Models\Brands;
use App\Models\Categories;
use App\Models\Products;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductsApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        Storage::fake('public');
        $this->user = User::factory()->create();
        $this->user->assignRole('admin'); 
        Sanctum::actingAs($this->user);
    }

    /** @test */
    public function it_can_list_products(): void
    {
        Products::factory()->count(5)->create();

        $response = $this->getJson('/api/V1/products');

        $response->assertStatus(200)
            ->assertJson(['status' => true])
            ->assertJsonStructure(['data', 'meta']);
    }

    /** @test */
    public function it_can_create_a_product(): void
    {
        $category = Categories::factory()->create();

        $response = $this->postJson('/api/V1/products', [
            'category_id' => $category->id,
            'name'        => 'New Product',
            'description' => 'Some desc',
            'price'       => 100,
            'sku'         => 'SKU-NEW-001',
            'status'      => 'active',
        ]);

        $response->assertStatus(201)
            ->assertJson(['status' => true]);

        $this->assertDatabaseHas('products', ['sku' => 'SKU-NEW-001']);
    }

    /** @test */
    public function it_validates_required_fields(): void
    {
        $response = $this->postJson('/api/V1/products', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['category_id', 'name', 'description', 'price', 'sku', 'status']);
    }

    /** @test */
    public function it_can_show_a_product(): void
    {
        $product = Products::factory()->create();

        $response = $this->getJson("/api/V1/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson(['status' => true, 'data' => ['id' => $product->id]]);
    }

    /** @test */
    public function it_can_update_a_product(): void
    {
        $product = Products::factory()->create(['name' => 'Old', 'user_id' => $this->user->id]);

        $response = $this->putJson("/api/V1/products/{$product->id}", [
            'name' => 'Updated',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'id'   => $product->id,
            'name' => 'Updated',
        ]);
    }

    /** @test */
    public function it_can_delete_a_product(): void
    {
        $product = Products::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("/api/v1/products/{$product->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    /** @test */
    public function v2_returns_meta(): void
    {
        Products::factory()->count(3)->create();

        $response = $this->getJson('/api/V2/products');

        $response->assertStatus(200)
            ->assertJsonPath('meta.version', 'V2');
    }

    /** @test */
    public function it_returns_401_when_unauthenticated(): void
    {
        $this->app['auth']->forgetGuards();

        $response = $this->getJson('/api/V1/products');

        $response->assertStatus(401);
    }
}