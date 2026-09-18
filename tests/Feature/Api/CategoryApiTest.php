<?php

namespace Tests\Feature\Api;

use App\Models\Categories;
use App\Models\Products;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryApiTest extends TestCase
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
    public function it_can_list_categories(): void
    {
        Categories::factory()->count(3)->create();

        $response = $this->getJson('/api/V1/categories');

        $response->assertStatus(200)
            ->assertJson(['status' => true])
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_filters_by_search(): void
    {
        Categories::factory()->create(['name' => 'Electronics']);
        Categories::factory()->create(['name' => 'Fashion']);

        $response = $this->getJson('/api/V1/categories?search=Electro');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function it_filters_by_status(): void
    {
        Categories::factory()->count(2)->create(['status' => true]);
        Categories::factory()->inactive()->create();

        $response = $this->getJson('/api/V1/categories?status=1');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

  

    /** @test */
    public function it_can_create_a_category(): void
    {
        $response = $this->postJson('/api/V1/categories', [
            'name'=>'Test Category',
            'description'=>'A test description',
            'status'=> true,
        ]);

        $response->assertStatus(201)
            ->assertJson(['status' => true]);

        $this->assertDatabaseHas('categories', ['name' => 'Test Category']);
    }

    /** @test */
    public function it_generates_slug_automatically(): void
    {
        $response = $this->postJson('/api/V1/categories', [
            'name'        => 'Test Category Name',
            'description' => 'desc',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('categories', [
            'slug' => 'test-category-name',
        ]);
    }

    /** @test */
    public function it_can_create_with_image(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('cat.png');

        $response = $this->postJson('/api/V1/categories', [
            'name'=>'With Image',
            'description'=>'desc',
            'image'=> $file,
        ]);

        $response->assertStatus(201);

        $category = Categories::where('name', 'With Image')->first();
        Storage::disk('public')->assertExists($category->image);
    }

    /** @test */
    public function it_validates_required_fields(): void
    {
        $response = $this->postJson('/api/V1/categories', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'description']);
    }

    /** @test */
    public function it_validates_unique_slug(): void
    {
        Categories::factory()->create(['slug' => 'dup-slug']);

        $response = $this->postJson('/api/V1/categories', [
            'name'        => 'New',
            'slug'        => 'dup-slug',
            'description' => 'desc',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['slug']);
    }

    /** @test */
    public function it_rejects_invalid_image(): void
    {
        $file = UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf');

        $response = $this->postJson('/api/v1/categories', [
            'name'        => 'Test',
            'description' => 'desc',
            'image'       => $file,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['image']);
    }

    /** @test */
    public function it_can_show_a_category(): void
    {
        $category = Categories::factory()->create();

        $response = $this->getJson("/api/v1/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'data'   => ['id' => $category->id],
            ]);
    }

    /** @test */
    public function it_returns_404_if_not_found(): void
    {
        $response = $this->getJson('/api/V1/categories/99999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_includes_products_count_in_show(): void
    {
        $category = Categories::factory()->create();
        Products::factory()->count(2)->create(['category_id' => $category->id]);

        $response = $this->getJson("/api/V1/categories/{$category->id}");

        $response->assertStatus(200);
       
        $response->assertJsonPath('data.products_count', 2);
    }

    /* ============ UPDATE ============ */

    /** @test */
    public function it_can_update_a_category(): void
    {
        $category = Categories::factory()->create(['name' => 'Old']);

        $response = $this->putJson("/api/V1/categories/{$category->id}", [
            'name' => 'Updated',
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => true]);

        $this->assertDatabaseHas('categories', [
            'id'   => $category->id,
            'name' => 'Updated',
        ]);
    }

    /** @test */
    public function it_can_update_with_new_image(): void
    {
        $oldFile = UploadedFile::fake()->image('old.png');
        $oldPath = $oldFile->store('categories', 'public');

        $category = Categories::factory()->create(['image' => $oldPath]);

        $newFile = UploadedFile::fake()->image('new.png');

        $response = $this->putJson("/api/V1/categories/{$category->id}", [
            'image' => $newFile,
        ]);

        $response->assertStatus(200);

        $this->assertFalse(Storage::disk('public')->exists($oldPath));
        $this->assertTrue(Storage::disk('public')->exists($category->fresh()->image));
    }

    /** @test */
    public function it_allows_same_slug_on_update(): void
    {
        $category = Categories::factory()->create(['slug' => 'my-slug']);

        $response = $this->putJson("/api/V1/categories/{$category->id}", [
            'name' => 'Updated',
            'slug' => 'my-slug',
        ]);

        $response->assertStatus(200);
    }


    /** @test */
    public function it_can_delete_a_category(): void
    {
        $category = Categories::factory()->create();

        $response = $this->deleteJson("/api/V1/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJson(['status' => true]);

        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }

    /** @test */
    public function it_cannot_delete_category_with_products(): void
    {
        $category = Categories::factory()->create();
        Products::factory()->create(['category_id' => $category->id]);

        $response = $this->deleteJson("/api/V1/categories/{$category->id}");

        $response->assertStatus(500)
            ->assertJson(['status' => false]);

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }


    /** @test */
    public function v2_index_returns_meta(): void
    {
        Categories::factory()->count(2)->create(['status' => true]);
        Categories::factory()->inactive()->create();

        $response = $this->getJson('/api/V2/categories');

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'meta', 'data'])
            ->assertJsonPath('meta.version', 'V2')
            ->assertJsonPath('meta.total', 3);
    }

    /** @test */
    public function v2_show_includes_products_count(): void
    {
        $category = Categories::factory()->create();
        Products::factory()->count(3)->create(['category_id' => $category->id]);

        $response = $this->getJson("/api/V2/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJsonPath('meta.products_count', 3);
    }


    /** @test */
    public function it_returns_401_when_unauthenticated(): void
    {
        $this->app['auth']->forgetGuards();

        $response = $this->getJson('/api/V1/categories');

        $response->assertStatus(401);
    }
}