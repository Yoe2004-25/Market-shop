<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Brands; 
use App\DTOs\Brands\CreateBrandsDTO; 
use App\Repositories\BrandsRepository ; 
class BrandCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    protected Brands $brand  ; 

    protected BrandsRepository $repository ; 
    
    public function __construct(Brands $brand)
    {
        $this->brand = $brand ; 
    }

    public function execute(CreateBrandsDTO $dto): Brands
    {
        $data = $dto->toArray();

        if ($dto->logo) {
            $data['logo'] = $dto->logo->store('brands', 'public');
        }

        $brand = $this->repository->create($data);

   
        event(new BrandCreated($brand));

        return $brand;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}