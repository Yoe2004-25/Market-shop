<?php

namespace Tests\Unit\Services;

use App\DTOs\Coupons\CreateCouponsDTO;
use App\DTOs\Coupons\UpdateCouponsDTO;
use App\Models\Coupons;
use App\Services\CouponsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CouponsServiceTest extends TestCase
{
    use RefreshDatabase;

    private CouponsService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CouponsService::class);
        Cache::flush();
    }

    /** @test */
    public function it_creates_a_coupon(): void
    {
        $dto = new CreateCouponsDTO(
            name:        'Test Coupon',
            code:        'TEST10',
            type:        'percentage',
            value:       10,
            expire_date: now()->addMonth()->toDateTimeString(),
            usage_limit: 50,
            status:      'active',
        );

        $coupon = $this->service->create($dto);

        $this->assertInstanceOf(Coupons::class, $coupon);
        $this->assertEquals('TEST10', $coupon->code);
    }

    /** @test */
    public function it_updates_a_coupon(): void
    {
        $coupon = Coupons::factory()->create(['value' => 10]);

        $dto = new UpdateCouponsDTO(value: 30);

        $updated = $this->service->update($coupon, $dto);

        $this->assertEquals(30, $updated->value);
    }

    /** @test */
    public function it_deletes_a_coupon(): void
    {
        $coupon = Coupons::factory()->create();

        $result = $this->service->delete($coupon);

        $this->assertTrue($result);
        $this->assertSoftDeleted('coupons', ['id' => $coupon->id]);
    }

    /** @test */
    public function it_validates_and_applies_a_percentage_coupon(): void
    {
        Coupons::factory()->create([
            'code'        => 'PERCENT10',
            'type'        => 'percentage',
            'value'       => 10,
            'status'      => 'active',
            'expire_date' => now()->addDay(),
        ]);

        $result = $this->service->validateAndApply('PERCENT10', 1000);

        $this->assertTrue($result['valid']);
        $this->assertEquals(100, $result['discount']); // 10% of 1000
    }

    /** @test */
    public function it_validates_and_applies_a_fixed_coupon(): void
    {
        Coupons::factory()->create([
            'code'        => 'FIXED50',
            'type'        => 'fixed',
            'value'       => 50,
            'status'      => 'active',
            'expire_date' => now()->addDay(),
        ]);

        $result = $this->service->validateAndApply('FIXED50', 200);

        $this->assertTrue($result['valid']);
        $this->assertEquals(50, $result['discount']);
    }

    /** @test */
    public function it_rejects_expired_coupon(): void
    {
        Coupons::factory()->create([
            'code'        => 'EXPIRED',
            'type'        => 'fixed',
            'value'       => 50,
            'status'      => 'active',
            'expire_date' => now()->subDay(),
        ]);

        $result = $this->service->validateAndApply('EXPIRED', 200);

        $this->assertFalse($result['valid']);
    }

    /** @test */
    public function it_rejects_unknown_coupon_code(): void
    {
        $result = $this->service->validateAndApply('UNKNOWN', 100);

        $this->assertFalse($result['valid']);
        $this->assertEquals('Coupon not found.', $result['message']);
    }

    /** @test */
    public function it_caps_fixed_discount_at_total(): void
    {
        Coupons::factory()->create([
            'code'        => 'BIG',
            'type'        => 'fixed',
            'value'       => 500,
            'status'      => 'active',
            'expire_date' => now()->addDay(),
        ]);

        $result = $this->service->validateAndApply('BIG', 100);

        $this->assertEquals(100, $result['discount']); // لا يتجاوز الإجمالي
    }
}