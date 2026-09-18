<?php

namespace Tests\Unit\Actions;

use App\Actions\OrdersItems\DeleteOrdersItemsAction;
use App\Models\OrdersItems;
use App\Repositories\OrdersItemsRepositoryInterface;
use Mockery;
use Tests\TestCase;

class DeleteOrdersItemsActionTest extends TestCase
{
    /** @test */
    public function it_calls_repository_delete(): void
    {
        $item = new OrdersItems(['id' => 1]);

        $repositoryMock = Mockery::mock(OrdersItemsRepositoryInterface::class);
        $repositoryMock
            ->shouldReceive('delete')
            ->once()
            ->with($item)
            ->andReturn(true);

        $action = new DeleteOrdersItemsAction($repositoryMock);
        $result = $action->execute($item);

        $this->assertTrue($result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}