<?php

namespace Tests\Unit\Repositories;

use App\Models\Brands;
use App\Models\Products;
use App\Repositories\BrandsRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandsRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private BrandsRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new BrandsRepository(new Brands());
    }

    /** @test */
    public function it_can_create_a_brand(): void
    {
        $brand = $this->repository->create([
            'name' => 'Nike',
            'logo' => 'brands/nike.png',
        ]);

        $this->assertInstanceOf(Brands::class, $brand);
        $this->assertDatabaseHas('brands', [
            'name' => 'Nike',
            'logo' => 'brands/nike.png',
        ]);
    }

    /** @test */
    public function it_can_find_a_brand_by_id(): void
    {
        $brand = Brands::factory()->create(['name' => 'Adidas']);

        $found = $this->repository->find($brand->id);

        $this->assertNotNull($found);
        $this->assertEquals('Adidas', $found->name);
    }

    /** @test */
    public function it_returns_null_when_brand_not_found(): void
    {
        $found = $this->repository->find(99999);

        $this->assertNull($found);
    }

    /** @test */
    public function it_throws_exception_when_find_or_fail_not_found(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->repository->findOrFail(99999);
    }

    /** @test */
    public function it_can_update_a_brand(): void
    {
        $brand = Brands::factory()->create(['name' => 'Old']);

        $updated = $this->repository->update($brand, ['name' => 'New']);

        $this->assertEquals('New', $updated->name);
        $this->assertDatabaseHas('brands', ['id' => $brand->id, 'name' => 'New']);
    }

    /** @test */
    public function it_can_soft_delete_a_brand(): void
    {
        $brand = Brands::factory()->create();

        $result = $this->repository->delete($brand);

        $this->assertTrue($result);
        $this->assertSoftDeleted('brands', ['id' => $brand->id]);
    }

    /** @test */
    public function it_can_get_all_brands_with_search_filter(): void
    {
        Brands::factory()->create(['name' => 'Nike']);
        Brands::factory()->create(['name' => 'Adidas']);
        Brands::factory()->create(['name' => 'Puma']);

        $filtered = $this->repository->all(['search' => 'Nik']);

        $this->assertCount(1, $filtered);
        $this->assertEquals('Nike', $filtered->first()->name);
    }

    /** @test */
    public function it_can_find_brand_by_name(): void
    {
        Brands::factory()->create(['name' => 'Sony']);

        $found = $this->repository->findByName('Sony');

        $this->assertNotNull($found);
        $this->assertEquals('Sony', $found->name);
    }

    /** @test */
    public function it_loads_products_relationship(): void
    {
        $brand = Brands::factory()->create();
        Products::factory()->count(2)->create(['brand_id' => $brand->id]);

        $found = $this->repository->findOrFail($brand->id);

        $this->assertTrue($found->relationLoaded('products'));
        $this->assertCount(2, $found->products);
    }
}