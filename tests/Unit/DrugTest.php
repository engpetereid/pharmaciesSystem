<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Services\Admin\DrugService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DrugTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    use RefreshDatabase;
    public function test_create_drug()
    {
        $service = new DrugService();
        $category = Category::factory()->create();
        $service->create([
            'name'=>'test drug',
            'price'=>100,
            'category_id'=>$category->id,
        ]);
        $this->assertDatabaseHas('drugs', [
            'name' => 'test drug'
        ]);
    }
    public function test_update_drug()
    {
        $service = new DrugService();
        $category = Category::factory()->create();
        $drug = $service->create([
            'name'=>'test drug',
            'price'=>100,
            'category_id'=>$category->id,
        ]);
        $service->update($drug->id,[
            'name'=>'test drug update',
            'price'=>100,
            'category_id'=>$category->id,
        ]);
        $this->assertDatabaseHas('drugs', [
            'name' => 'test drug update'
        ]);
    }
    public function test_delete_drug()
    {
        $service = new DrugService();
        $category = Category::factory()->create();;
        $drug= $service->create([
            'name'=>'drug',
            'price'=>100,
            'category_id'=>$category->id,
        ]);
        $service->delete($drug->id);
        $this->assertDatabaseMissing('drugs', [
            'name' => 'drug'
        ]);
    }
}
