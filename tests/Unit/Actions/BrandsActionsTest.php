<?php

namespace Tests\Unit\Actions;

use App\Actions\Brands\CreateBrandsAction;
use App\Actions\Brands\DeleteBrandsAction;
use App\Actions\Brands\UpdateBrandsAction;
use App\DTOs\Brands\CreateBrandsDTO;
use App\DTOs\Brands\UpdateBrandsDTO;
use App\Models\Brands;
use App\Repositories\BrandsRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class BrandsActionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function create_action_calls_repository_create(): void
    {
        $file = UploadedFile::fake()->image('test.png');
        $dto = new CreateBrandsDTO(name: 'Test', logo: $file);

        $expectedBrand = Brands::factory()->make(['id' => 1]);

        $repository = Mockery::mock(BrandsRepositoryInterface::class);
        $repository->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($data) {
                return $data['name'] === 'Test' && isset($data['logo']);
            }))
            ->andReturn($expectedBrand);

        $action = new CreateBrandsAction($repository);
        $result = $action->execute($dto);

        $this->assertSame($expectedBrand, $result);
    }

    /** @test */
    public function update_action_calls_repository_update(): void
    {
        $brand = Brands::factory()->create();
        $dto = new UpdateBrandsDTO(name: 'Updated');

        $repository = Mockery::mock(BrandsRepositoryInterface::class);
        $repository->shouldReceive('update')
            ->once()
            ->with($brand, ['name' => 'Updated'])
            ->andReturn($brand);

        $action = new UpdateBrandsAction($repository);
        $result = $action->execute($brand, $dto);

        $this->assertSame($brand, $result);
    }

    /** @test */
    public function delete_action_calls_repository_delete(): void
    {
        $brand = Brands::factory()->create();

        $repository = Mockery::mock(BrandsRepositoryInterface::class);
        $repository->shouldReceive('delete')
            ->once()
            ->with($brand)
            ->andReturn(true);

        $action = new DeleteBrandsAction($repository);
        $result = $action->execute($brand);

        $this->assertTrue($result);
    }

    /** @test */
    public function delete_action_removes_logo_from_storage(): void
    {
        $file = UploadedFile::fake()->image('brand.png');
        $path = $file->store('brands', 'public');
        $brand = Brands::factory()->create(['logo' => $path]);

        $action = app(DeleteBrandsAction::class);
        $action->execute($brand);

        $this->assertFalse(Storage::disk('public')->exists($path));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}