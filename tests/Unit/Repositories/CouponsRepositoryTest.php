<?php

namespace Tests\Unit\Repositories;

use App\Models\Coupons;
use App\Repositories\CouponsRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponsRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private CouponsRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CouponsRepository(new Coupons());
    }

    /** @test */
    public function it_can_create_a_coupon(): void
    {
        $data = [
            'name'        => 'Summer Sale',
            'code'        => 'SUMMER2025',
            'type'        => 'percentage',
            'value'       => 15,
            'expire_date' => now()->addMonth(),
            'usage_limit' => 100,
            'status'      => 'active',
        ];

        $coupon = $this->repository->create($data);

        $this->assertInstanceOf(Coupons::class, $coupon);
        $this->assertDatabaseHas('coupons', ['code' => 'SUMMER2025']);
    }

    /** @test */
    public function it_finds_coupon_by_code(): void
    {
        Coupons::factory()->create(['code' => 'FINDME']);

        $found = $this->repository->findByCode('FINDME');

        $this->assertNotNull($found);
        $this->assertEquals('FINDME', $found->code);
    }

    /** @test */
    public function it_returns_null_if_code_not_found(): void
    {
        $found = $this->repository->findByCode('NOPE');

        $this->assertNull($found);
    }

    /** @test */
    public function it_updates_a_coupon(): void
    {
        $coupon = Coupons::factory()->create(['value' => 10]);

        $updated = $this->repository->update($coupon, ['value' => 25]);

        $this->assertEquals(25, $updated->value);
    }

    /** @test */
    public function it_soft_deletes_a_coupon(): void
    {
        $coupon = Coupons::factory()->create();

        $result = $this->repository->delete($coupon);

        $this->assertTrue($result);
        $this->assertSoftDeleted('coupons', ['id' => $coupon->id]);
    }

    /** @test */
    public function it_gets_only_active_coupons(): void
    {
        Coupons::factory()->create([
            'status'      => 'active',
            'expire_date' => now()->addDay(),
        ]);

        Coupons::factory()->create([
            'status'      => 'nonactive',
            'expire_date' => now()->addDay(),
        ]);

        Coupons::factory()->create([
            'status'      => 'active',
            'expire_date' => now()->subDay(), // expired
        ]);

        $active = $this->repository->getActive();

        $this->assertCount(1, $active);
    }
}