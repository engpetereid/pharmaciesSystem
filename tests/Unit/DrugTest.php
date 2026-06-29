<?php

namespace Tests\Unit;

use App\DTOs\SaveDrugDTO;
use App\Models\Drug;
use App\Repositories\IDrugRepository;
use App\Services\Admin\IDrugService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery\MockInterface;
use Tests\TestCase;

class DrugTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_create_drug_using_mocking()
    {
        $dto = new SaveDrugDTO(name: 'Panadol', price: 50, category_id: 1);

        $fakeDrug = new Drug([
            'id' => 1,
            'name' => 'Panadol',
            'price' => 50,
            'category_id' => 1
        ]);

        $this->mock(IDrugRepository::class, function (MockInterface $mock) use ($dto, $fakeDrug) {
            $mock->shouldReceive('store')
            ->once()
            ->with($dto)
            ->andReturn($fakeDrug);
        });

        $service = app(IDrugService::class);
        $result = $service->store($dto);

        $this->assertEquals('Panadol', $result->name);
        $this->assertEquals(50, $result->price);
    }
    public function test_update_drug()
    {
        $dto = new SaveDrugDTO(name: 'Panadol', price: 100, category_id: 1);

        $drug = new Drug([
            'id' => 1,
            'name' => 'Panadol',
            'price' => 50,
            'category_id' => 1
        ]);

        $newDrug = new Drug([
            'id' => 1,
            'name' => 'Panadol',
            'price' => 100,
            'category_id' => 1
        ]);
        $this->mock(IDrugRepository::class, function (MockInterface $mock) use ($dto, $drug, $newDrug) {
            $mock->shouldReceive('update')
                ->once()
                ->with($drug,$dto)
                ->andReturn($newDrug);
        });
        $service = app(IDrugService::class);
        $result = $service->update($drug,$dto);

        $this->assertEquals('Panadol', $result->name);
        $this->assertEquals(100, $result->price);
    }
    public function test_delete_drug()
    {
        $fakeDrug = new Drug([
            'id' => 1,
            'name' => 'Panadol'
        ]);
        $this->mock(IDrugRepository::class, function (MockInterface $mock) use ($fakeDrug) {
            $mock->shouldReceive('delete')
                ->once()
                ->with($fakeDrug)
                ->andReturn(true);
        });
        $service = app(IDrugService::class);
        $result = $service->delete($fakeDrug);
        $this->assertTrue($result);
    }
    public function test_find_drug_by_id_throws_exception_if_not_found()
    {
        $invalidId = 999;

        $this->mock(IDrugRepository::class, function (MockInterface $mock) use ($invalidId) {
            $mock->shouldReceive('findById')
                ->once()
                ->with($invalidId)
                ->andThrow(new ModelNotFoundException('Drug not found'));
        });

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('Drug not found');

        $service = app(IDrugService::class);
        $service->findById($invalidId);
    }
}
