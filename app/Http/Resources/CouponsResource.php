<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'code'         => $this->code,
            'type'         => $this->type,
            'value'        => (float) $this->value,
            'expire_date'  => $this->expire_date?->toDateTimeString(),
            'usage_limit'  => $this->usage_limit,
            'status'       => $this->status,
            'is_valid'     => $this->isValid(),             
            'users_count'  => $this->whenCounted('users'),  
            'created_at'   => $this->created_at?->toDateTimeString(),
            'updated_at'   => $this->updated_at?->toDateTimeString(),
        ];
    }
}