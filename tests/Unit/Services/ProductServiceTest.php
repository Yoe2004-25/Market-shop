<?php

namespace Tests\Unit\Services;

use App\DTOs\Products\CreateProductDTO;
use App\DTOs\Products\UpdateProductDTO;
use App\Models\Brands;
use App\Models\Categories;
use App\Models\Products;
use App\Models\User;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProductService $service;
    private User $user;
    private Categories $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ProductService::class);
        $this->user = User::factory()->create();
        $this->category = Categories::factory()->create();
        Cache::flush();
        Storage::fake('public');
    }

    /** @test */
    public function it_creates_a_product(): void
    {
        $dto = new CreateProductDTO(
            category_id: $this->category->id,
            brand_id:    null,
            user_id:     $this->user->id,
            name:        'Test',
            slug:        'test',
            description: 'desc',
            price:       100,
            discount:    0,
            stock:       5,
            sku:         'SKU-001',
            image:       null,
            status:      'active',
        );

        $product = $this->service->create($dto);

        $this->assertInstanceOf(Products::class, $product);
        $this->assertEquals('Test', $product->name);
    }

    /** @test */
    public function it_creates_with_image(): void
    {
        $file = UploadedFile::fake()->image('p.png');

        $dto = new CreateProductDTO(
            category_id: $this->category->id,
            brand_id:    null,
            user_id:     $this->user->id,
            name:        'With Image',
            slug:        'with-image',
            description: 'desc',
            price:       100,
            discount:    0,
            stock:       5,
            sku:         'SKU-002',
            image:       $file,
            status:      'active',
        );

        $product = $this->service->create($dto);

        $this->assertNotNull($product->image);
        Storage::assertExists($product->image);
    }

    /** @test */
    public function it_updates_a_product(): void
    {
        $product = Products::factory()->create(['name' => 'Old']);

        $dto = new UpdateProductDTO(name: 'New');

        $updated = $this->service->update($product, $dto);

        $this->assertEquals('New', $updated->name);
    }

    /** @test */
    public function it_deletes_a_product(): void
    {
        $product = Products::factory()->create();

        $result = $this->service->delete($product);

        $this->assertTrue($result);
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    /** @test */
    public function it_caches_get_all(): void
    {
        Products::factory()->count(3)->create();

        $first = $this->service->getAll();
        Products::factory()->create();
        $second = $this->service->getAll();

        $this->assertEquals($first->count(), $second->count());
    }
}