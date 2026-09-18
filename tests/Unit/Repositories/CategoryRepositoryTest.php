<?php

namespace Tests\Unit\Repositories;

use App\Models\Categories;
use App\Models\Products;
use App\Repositories\CategoryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private CategoryRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CategoryRepository(new Categories());
    }

    /** @test */
    public function it_can_create_a_category(): void
    {
        $data = [
            'name'=>'Electronics',
            'slug'=>'electronics',
            'description'=>'All electronic devices',
            'status'=> true,
        ];

        $category = $this->repository->create($data);

        $this->assertInstanceOf(Categories::class, $category);
        $this->assertDatabaseHas('categories', ['slug' => 'electronics']);
    }

    /** @test */
    public function it_can_find_a_category_by_id(): void
    {
        $category = Categories::factory()->create(['name' => 'Fashion']);

        $found = $this->repository->findById($category->id);

        $this->assertNotNull($found);
        $this->assertEquals('Fashion', $found->name);
    }

    /** @test */
    public function it_returns_null_if_category_not_found(): void
    {
        $this->assertNull($this->repository->findById(99999));
    }

    /** @test */
    public function it_throws_exception_when_find_or_fail_not_found(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->repository->findOrFail(99999);
    }

    /** @test */
    public function it_can_update_a_category(): void
    {
        $category = Categories::factory()->create(['name' => 'Old']);

        $updated = $this->repository->update($category->id, ['name' => 'New']);

        $this->assertEquals('New', $updated->name);
        $this->assertDatabaseHas('categories', [
            'id'   => $category->id,
            'name' => 'New',
        ]);
    }

    /** @test */
    public function it_can_soft_delete_a_category(): void
    {
        $category = Categories::factory()->create();

        $result = $this->repository->delete($category->id);

        $this->assertTrue($result);
        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }

    /** @test */
    public function it_can_filter_categories_by_search(): void
    {
        Categories::factory()->create(['name' => 'Electronics']);
        Categories::factory()->create(['name' => 'Fashion']);
        Categories::factory()->create(['name' => 'Home']);

        $filtered = $this->repository->all(['search' => 'Elec']);

        $this->assertCount(1, $filtered);
        $this->assertEquals('Electronics', $filtered->first()->name);
    }

    /** @test */
    public function it_can_filter_categories_by_status(): void
    {
        Categories::factory()->create(['status' => true]);
        Categories::factory()->create(['status' => true]);
        Categories::factory()->inactive()->create();

        $active = $this->repository->all(['status' => 1]);

        $this->assertCount(2, $active);
    }

   
    public function it_can_find_by_slug(): void
    {
        Categories::factory()->create(['slug' => 'find-me']);

        $found = $this->repository->findBySlug('find-me');

        $this->assertNotNull($found);
        $this->assertEquals('find-me', $found->slug);
    }

    /** @test */
    public function it_loads_products_count_relationship(): void
    {
        $category = Categories::factory()->create();
        Products::factory()->count(3)->create(['category_id' => $category->id]);

        $found = $this->repository->findOrFail($category->id);

        $this->assertCount(3, $found->products);
    }
}