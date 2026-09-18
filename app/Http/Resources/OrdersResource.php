<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vtiful\Kernel\Format;
use App\Http\Resources\CouponsResource;
use App\Http\Resources\OrdersItemsResource;
class OrdersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'coupon'  => CouponsResource::collection($this->whenLoaded('coupon')),
            'items'   => OrdersItemsResource::collection($this->whenLoaded('items')),
            'status'=>$this->status, 
            'payment_status'=>$this->payment_status, 
            'total'=>$this->total, 
            'shipping_cost'=>$this->shipping_cost,
            'tax'=>$this->tax, 
            'grand_total'=>$this->grand_total,
            'created_at'=>$this->created_at?->format('Y-m-d H:s:h'),
            'updated_at'=>$this->updated_at?->format('Y-m-d H:s:h'),
        ];
    }
}