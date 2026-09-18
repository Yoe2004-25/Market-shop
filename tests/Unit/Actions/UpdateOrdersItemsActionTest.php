<?php

namespace Tests\Unit\Actions;

use App\Actions\OrdersItems\UpdateOrdersItemsAction;
use App\DTOs\OrdersItems\UpdateOrdersItemsDTO;
use App\Models\OrdersItems;
use App\Repositories\OrdersItemsRepositoryInterface;
use Mockery;
use Tests\TestCase;

class UpdateOrdersItemsActionTest extends TestCase
{
    /** @test */
    public function it_calls_repository_update_with_correct_data(): void
    {
        $item = new OrdersItems(['id' => 1]);
        $dto = new UpdateOrdersItemsDTO(quantity: 5, subtotal: 2500);

        $repositoryMock = Mockery::mock(OrdersItemsRepositoryInterface::class);
        $repositoryMock
            ->shouldReceive('update')
            ->once()
            ->with($item, $dto->toArray())
            ->andReturn($item);

        $action = new UpdateOrdersItemsAction($repositoryMock);
        $result = $action->execute($item, $dto);

        $this->assertSame($item, $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}