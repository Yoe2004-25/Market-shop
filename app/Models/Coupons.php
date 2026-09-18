<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupons extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'code', 'type', 'value',
        'expire_date', 'usage_limit', 'status',
    ];

    protected $casts = [
        'value'       => 'decimal:2',
        'expire_date' => 'datetime',
        'usage_limit' => 'integer',
    ];

    public const TYPE_FIXED      = 'fixed';
    public const TYPE_PERCENTAGE = 'percentage';
    public const STATUS_ACTIVE   = 'active';
    public const STATUS_INACTIVE = 'nonactive';

    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_user')
            ->withPivot('time_of_coupon')
            ->withTimestamps();
    }

    public function orders()   // ✅ hasMany
    {
        return $this->hasMany(Orders::class, 'coupon_id');
    }

    public function scopeActive($q)
    {
        return $q->where('status', self::STATUS_ACTIVE)
            ->where(function ($q) {
                $q->whereNull('expire_date')
                  ->orWhere('expire_date', '>', now());
            });
    }

    public function scopeExpired($q)
    {
        return $q->whereNotNull('expire_date')
            ->where('expire_date', '<', now());
    }

    public function isValid(): bool
    {
        return $this->status === self::STATUS_ACTIVE
            && (is_null($this->expire_date) || $this->expire_date->isFuture());
    }

    public function calculateDiscount(float $total): float
    {
        return $this->type === self::TYPE_PERCENTAGE
            ? round($total * ($this->value / 100), 2)
            : min($this->value, $total);
    }
}