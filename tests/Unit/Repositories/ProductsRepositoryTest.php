<?php

namespace Tests\Unit\Repositories;

use App\Models\Brands;
use App\Models\Categories;
use App\Models\Products;
use App\Models\User;
use App\Repositories\ProductsRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ProductsRepository $repository;
    private User $user;
    private Categories $category;
    private Brands $brand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ProductsRepository(new Products());
        $this->user = User::factory()->create();
        $this->category = Categories::factory()->create();
        $this->brand = Brands::factory()->create();
    }

    /** @test */
    public function it_can_create_a_product(): void
    {
        $product = $this->repository->create([
            'category_id' => $this->category->id,
            'brand_id'    => $this->brand->id,
            'user_id'     => $this->user->id,
            'name'        => 'Test Product',
            'slug'        => 'test-product',
            'description' => 'Some description',
            'price'       => 100,
            'discount'    => 10,
            'stock'       => 5,
            'sku'         => 'SKU-001',
            'status'      => 'active',
        ]);

        $this->assertInstanceOf(Products::class, $product);
        $this->assertDatabaseHas('products', ['sku' => 'SKU-001']);
    }

    /** @test */
    public function it_can_find_by_id(): void
    {
        $product = Products::factory()->create();

        $found = $this->repository->find($product->id);

        $this->assertNotNull($found);
        $this->assertEquals($product->id, $found->id);
    }

    /** @test */
    public function it_can_filter_by_search(): void
    {
        Products::factory()->create(['name' => 'iPhone 15']);
        Products::factory()->create(['name' => 'Samsung Galaxy']);

        $results = $this->repository->all(['search' => 'iPhone']);

        $this->assertCount(1, $results);
    }

    /** @test */
    public function it_can_filter_by_category(): void
    {
        Products::factory()->count(3)->create(['category_id' => $this->category->id]);
        Products::factory()->create(['category_id' => Categories::factory()->create()->id]);

        $results = $this->repository->all(['category_id' => $this->category->id]);

        $this->assertCount(3, $results);
    }

    /** @test */
    public function it_can_soft_delete(): void
    {
        $product = Products::factory()->create();

        $result = $this->repository->delete($product);

        $this->assertTrue($result);
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    /** @test */
    public function it_paginates(): void
    {
        Products::factory()->count(25)->create();

        $paged = $this->repository->paginate(10);

        $this->assertEquals(10, $paged->count());
        $this->assertEquals(25, $paged->total());
    }
}