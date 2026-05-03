<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Tests\TestCase;
use App\Models\Drug;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DrugTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(
            User::factory()->create([
                'role' => 'admin'
            ]),
            'sanctum'
        );
    }
    public function test_get_drugs()
    {
        // Arrange
        Drug::factory()->count(3)->create();

        // Act
        $response = $this->getJson('/api/v1/drugs');

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => true
            ])
            ->assertJsonCount(3, 'data');
    }
    public function test_create_drug()
    {
        $category=Category::factory()->create();
        $data = [
            'name' => 'Test Drug',
            'price' => 100,
            'category_id' => $category->id,
        ];

        $response = $this->postJson('/api/v1/drugs', $data);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true
            ]) ->assertJsonPath('data.name', 'Test Drug');

        $this->assertDatabaseHas('drugs', [
            'name' => 'Test Drug'
        ]);
    }
    public function test_update_drug()
    {
        // Arrange
        $drug = Drug::factory()->create();
        $category=Category::factory()->create();
        $data = [
            'name' => 'Updated Drug',
            'price' => 100,
            'category_id' => $category->id,
        ];

        // Act
        $response = $this->putJson('/api/v1/drugs/' . $drug->id, $data);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => true
            ]);

        $this->assertDatabaseHas('drugs', [
            'id' => $drug->id,
            'name' => 'Updated Drug'
        ]);
    }
    public function test_delete_drug(){
        $drug = Drug::factory()->create();
        $response = $this->deleteJson('/api/v1/drugs/'.$drug->id);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'drug deleted successfully',
        ]);
    }
    public function test_Drug_validation()
    {
        $response = $this->postJson('/api/v1/drugs', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);

    }
}
