<?php

namespace Tests\Unit;

use App\DTOs\SaveCategoryDTO;
use App\Models\Category;
use App\Repositories\ICategoryRepository;
use App\Services\Admin\ICategoryService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery\MockInterface;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_create_Category_using_mocking()
    {
        $dto = new SaveCategoryDTO(name: 'category');

        $fakeCategory = new Category([
            'id' => 1,
            'name' => 'category',

        ]);

        $this->mock(ICategoryRepository::class, function (MockInterface $mock) use ($dto, $fakeCategory) {
            $mock->shouldReceive('store')
                ->once()
                ->with($dto)
                ->andReturn($fakeCategory);
        });

        $service = app(ICategoryService::class);
        $result = $service->store($dto);

        $this->assertEquals('category', $result->name);
    }
    public function test_update_Category()
    {
        $dto = new SaveCategoryDTO(name: 'update category');

        $Category = new Category([
            'id' => 1,
            'name' => 'category',
        ]);

        $newCategory = new Category([
            'id' => 1,
            'name' => 'update category',
        ]);
        $this->mock(ICategoryRepository::class, function (MockInterface $mock) use ($dto, $Category, $newCategory) {
            $mock->shouldReceive('update')
                ->once()
                ->with($Category,$dto)
                ->andReturn($newCategory);
        });
        $service = app(ICategoryService::class);
        $result = $service->update($Category,$dto);

        $this->assertEquals('update category', $result->name);
    }
    public function test_delete_Category()
    {
        $fakeCategory = new Category([
            'id' => 1,
            'name' => 'category',
        ]);
        $this->mock(ICategoryRepository::class, function (MockInterface $mock) use ($fakeCategory) {
            $mock->shouldReceive('delete')
                ->once()
                ->with($fakeCategory)
                ->andReturn(true);
        });
        $service = app(ICategoryService::class);
        $result = $service->delete($fakeCategory);
        $this->assertTrue($result);
    }
    public function test_find_Category_by_id_throws_exception_if_not_found()
    {
        $invalidId = 999;

        $this->mock(ICategoryRepository::class, function (MockInterface $mock) use ($invalidId) {
            $mock->shouldReceive('findById')
                ->once()
                ->with($invalidId)
                ->andThrow(new ModelNotFoundException('Category not found'));
        });

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('Category not found');

        $service = app(ICategoryService::class);
        $service->findById($invalidId);
    }
}
