<?php

namespace Tests\Unit\Services;

use App\DTOs\Brands\CreateBrandsDTO;
use App\DTOs\Brands\UpdateBrandsDTO;
use App\Events\BrandCreated;
use App\Models\Brands;
use App\Services\BrandsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BrandsServiceTest extends TestCase
{
    use RefreshDatabase;

    private BrandsService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(BrandsService::class);
        Cache::flush();
        Storage::fake('public');
    }

    /** @test */
    public function it_creates_a_brand_without_logo(): void
    {
        $dto = new CreateBrandsDTO(name: 'Test Brand');

        $brand = $this->service->create($dto);

        $this->assertInstanceOf(Brands::class, $brand);
        $this->assertEquals('Test Brand', $brand->name);
        $this->assertNull($brand->logo);
    }

    /** @test */
    public function it_creates_a_brand_with_logo(): void
    {
        $file = UploadedFile::fake()->image('brand.png');

        $dto = new CreateBrandsDTO(name: 'Brand With Logo', logo: $file);

        $brand = $this->service->create($dto);

        $this->assertNotNull($brand->logo);
        $this->assertTrue(Storage::disk('public')->exists($brand->logo));
    }

    /** @test */
    public function it_dispatches_brand_created_event(): void
    {
        Event::fake();

        $dto = new CreateBrandsDTO(name: 'Event Test');

        $this->service->create($dto);

        Event::assertDispatched(BrandCreated::class, function ($event) {
            return $event->brand->name === 'Event Test';
        });
    }

    /** @test */
    public function it_updates_a_brand_name(): void
    {
        $brand = Brands::factory()->create(['name' => 'Old Name']);

        $dto = new UpdateBrandsDTO(name: 'New Name');

        $updated = $this->service->update($brand, $dto);

        $this->assertEquals('New Name', $updated->name);
    }

    /** @test */
    public function it_replaces_logo_on_update(): void
    {
        $oldLogo = UploadedFile::fake()->image('old.png');
        $oldPath = $oldLogo->store('brands', 'public');

        $brand = Brands::factory()->create(['logo' => $oldPath]);

        $newLogo = UploadedFile::fake()->image('new.png');
        $dto = new UpdateBrandsDTO(logo: $newLogo);

        $updated = $this->service->update($brand, $dto);

      
        $this->assertFalse(Storage::disk('public')->exists($oldPath));
       
        $this->assertTrue(Storage::disk('public')->exists($updated->logo));
    }

    /** @test */
    public function it_deletes_a_brand_and_its_logo(): void
    {
        $file = UploadedFile::fake()->image('brand.png');
        $path = $file->store('brands', 'public');

        $brand = Brands::factory()->create(['logo' => $path]);

        $result = $this->service->delete($brand);

        $this->assertTrue($result);
        $this->assertSoftDeleted('brands', ['id' => $brand->id]);
        $this->assertFalse(Storage::disk('public')->exists($path));
    }

    /** @test */
    public function it_caches_get_all_result(): void
    {
        Brands::factory()->count(3)->create();

        $first = $this->service->getAll([]);

      
        Brands::factory()->create();

        
        $second = $this->service->getAll([]);

        $this->assertEquals($first->count(), $second->count());
    }

    /** @test */
    public function it_clears_cache_on_create(): void
    {
        $this->service->getAll([]);

        $dto = new CreateBrandsDTO(name: 'Cache Test');
        $this->service->create($dto);

        $brands = $this->service->getAll([]);

        $this->assertCount(1, $brands);
    }
}