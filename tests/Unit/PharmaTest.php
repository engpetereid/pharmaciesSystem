<?php

namespace Tests\Unit;

use App\DTOs\SavePharmaDTO;
use App\Models\Pharma;
use App\Repositories\IPharmaRepository;
use App\Services\Admin\IPharmaService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery\MockInterface;
use Tests\TestCase;

class PharmaTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_create_Pharma_using_mocking()
    {
        $dto = new SavePharmaDTO(name: 'pharma',user_id: 1);

        $fakePharma = new Pharma([
            'name' => 'pharma',
            'user_id' => 1,
        ]);

        $this->mock(IPharmaRepository::class, function (MockInterface $mock) use ($dto, $fakePharma) {
            $mock->shouldReceive('store')
                ->once()
                ->with($dto)
                ->andReturn($fakePharma);
        });

        $service = app(IPharmaService::class);
        $result = $service->store($dto);

        $this->assertEquals('pharma', $result->name);
    }
    public function test_update_Pharma()
    {
        $dto = new SavePharmaDTO(name: 'updated_pharma',user_id: 1);

        $Pharma = new Pharma([
            'name' => 'pharma',
            'user_id' => 1,
        ]);

        $newPharma = new Pharma([
            'name' => 'updated_pharma',
            'user_id' => 1,
        ]);
        $this->mock(IPharmaRepository::class, function (MockInterface $mock) use ($dto, $Pharma, $newPharma) {
            $mock->shouldReceive('update')
                ->once()
                ->with($Pharma,$dto)
                ->andReturn($newPharma);
        });
        $service = app(IPharmaService::class);
        $result = $service->update($Pharma,$dto);

        $this->assertEquals('updated_pharma', $result->name);
    }
    public function test_delete_Pharma()
    {
        $fakePharma = new Pharma([
            'name' => 'Panadol',
            'user_id' => 1,
        ]);
        $this->mock(IPharmaRepository::class, function (MockInterface $mock) use ($fakePharma) {
            $mock->shouldReceive('delete')
                ->once()
                ->with($fakePharma)
                ->andReturn(true);
        });
        $service = app(IPharmaService::class);
        $result = $service->delete($fakePharma);
        $this->assertTrue($result);
    }
    public function test_find_Pharma_by_id_throws_exception_if_not_found()
    {
        $invalidId = 999;

        $this->mock(IPharmaRepository::class, function (MockInterface $mock) use ($invalidId) {
            $mock->shouldReceive('findById')
                ->once()
                ->with($invalidId)
                ->andThrow(new ModelNotFoundException('Pharma not found'));
        });

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('Pharma not found');

        $service = app(IPharmaService::class);
        $service->findById($invalidId);
    }
}
