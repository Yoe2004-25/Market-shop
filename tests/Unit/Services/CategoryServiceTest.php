<?php

namespace Tests\Unit\Services;

use App\DTOs\Category\CategoryDTO;
use App\Models\Categories;
use App\Models\Products;
use App\Services\CategoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{
    use RefreshDatabase;

    private CategoryService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CategoryService::class);
        Cache::flush();
        Storage::fake('public');
    }

    /** @test */
    public function it_creates_a_category_without_image(): void
    {
        $dto = new CategoryDTO(
            name:        'Test Category',
            slug:        'test-category',
            description: 'A test description',
            image:       null,
            status:      true,
        );

        $category = $this->service->create($dto);

        $this->assertInstanceOf(Categories::class, $category);
        $this->assertEquals('Test Category', $category->name);
        $this->assertNull($category->image);
    }

    /** @test */
    public function it_creates_a_category_with_image(): void
    {
        $file = UploadedFile::fake()->image('category.png');

        $dto = new CategoryDTO(
            name:        'With Image',
            slug:        'with-image',
            description: 'Has image',
            image:       $file,
            status:      true,
        );

        $category = $this->service->create($dto);

        $this->assertNotNull($category->image);
        $this->assertTrue(Storage::disk('public')->exists($category->image));
    }

    /** @test */
    public function it_updates_a_category(): void
    {
        $category = Categories::factory()->create(['name' => 'Old Name']);

        $dto = new CategoryDTO(
            name:        'New Name',
            slug:        'new-name',
            description: 'Updated',
            image:       null,
            status:      true,
        );

        $updated = $this->service->update($category->id, $dto);

        $this->assertEquals('New Name', $updated->name);
    }

    /** @test */
    public function it_replaces_image_on_update(): void
    {
       
        $oldFile = UploadedFile::fake()->image('old.png');
        $oldPath = $oldFile->store('categories', 'public');

        $category = Categories::factory()->create(['image' => $oldPath]);

    
        $newFile = UploadedFile::fake()->image('new.png');

        $dto = new CategoryDTO(
            name:        $category->name,
            slug:        $category->slug,
            description: $category->description,
            image:       $newFile,
            status:      true,
        );

        $updated = $this->service->update($category->id, $dto);

        $this->assertFalse(Storage::disk('public')->exists($oldPath));
        $this->assertTrue(Storage::disk('public')->exists($updated->image));
    }

    /** @test */
    public function it_deletes_a_category(): void
    {
        $category = Categories::factory()->create();

        $result = $this->service->delete($category->id);

        $this->assertTrue($result);
        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }

    /** @test */
    public function it_deletes_category_image_on_delete(): void
    {
        $file = UploadedFile::fake()->image('cat.png');
        $path = $file->store('categories', 'public');

        $category = Categories::factory()->create(['image' => $path]);

        $this->service->delete($category->id);

        $this->assertFalse(Storage::disk('public')->exists($path));
    }

    /** @test */
    public function it_throws_exception_when_deleting_category_with_products(): void
    {
        $category = Categories::factory()->create();
        Products::factory()->create(['category_id' => $category->id]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cannot delete category with existing products.');

        $this->service->delete($category->id);
    }

    /** @test */
    public function it_caches_get_all_result(): void
    {
        Categories::factory()->count(3)->create();

        $first = $this->service->getAll();

      
        Categories::factory()->create();

        $second = $this->service->getAll();

        
        $this->assertEquals($first->count(), $second->count());
    }

    /** @test */
    public function it_clears_cache_on_create(): void
    {
        $this->service->getAll();

        $dto = new CategoryDTO(
            name:        'Cache Test',
            slug:        'cache-test',
            description: 'desc',
            image:       null,
            status:      true,
        );

        $this->service->create($dto);

        $categories = $this->service->getAll();

        $this->assertCount(1, $categories);
    }

    /** @test */
    public function it_clears_cache_on_update_and_delete(): void
    {
        $category = Categories::factory()->create();

        $this->service->getAll();

        $dto = new CategoryDTO(
            name:        'Updated',
            slug:        'updated',
            description: 'desc',
            image:       null,
            status:      true,
        );

        $this->service->update($category->id, $dto);

      
        $this->assertNull(
            Cache::tags([CategoryService::CACHE_TAG])
                ->get(CategoryService::CACHE_SINGLE . $category->id)
        );
    }

    /** @test */
    public function it_returns_category_with_products_count(): void
    {
        $category = Categories::factory()->create();
        Products::factory()->count(2)->create(['category_id' => $category->id]);

        $found = $this->service->getById($category->id);

        $this->assertCount(2, $found->products);
    }
}