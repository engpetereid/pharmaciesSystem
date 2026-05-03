<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Admin\CategoryService;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    use RefreshDatabase;
    public function test_create_category(): void
    {
        $service = new CategoryService();
        $service->create([
            'name'=>'category',
        ]);
        $this->assertDatabaseHas('categories',[
            'name'=>'category',
        ]);
    }
    public function test_update_category(): void
    {
        $service = new CategoryService();
        $category = $service->create([
            'name'=>'category',
        ]);
        $service ->update($category->id,[
            'name' => 'update category'
        ]);
        $this->assertDatabaseHas('categories',[
            'name'=>'update category',
        ]);

    }
    public function test_delete_category(): void
    {
        $service = new CategoryService();
        $category = $service->create([
            'name'=>'category',
        ]);
        $service ->delete($category->id);
        $this->assertDatabaseMissing('categories',[
            'name'=>'category',
        ]);
    }
}
