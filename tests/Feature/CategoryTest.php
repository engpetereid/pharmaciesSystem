<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
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
    public function test_get_categories()
    {
        // Arrange
        Category::factory()->count(3)->create();

        // Act
        $response = $this->getJson('/api/v1/categories');

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => true
            ])
            ->assertJsonCount(3, 'data');
    }
    public function test_create_category()
    {
        $data = [
            'name' => 'Test Category'
        ];

        $response = $this->postJson('/api/v1/categories', $data);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true
            ]) ->assertJsonPath('data.name', 'Test Category');

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category'
        ]);
    }
    public function test_update_category()
    {
        // Arrange
        $category = Category::factory()->create();

        $data = [
            'name' => 'Updated Category'
        ];

        // Act
        $response = $this->putJson('/api/v1/categories/' . $category->id, $data);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => true
            ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category'
        ]);
    }
    public function test_delete_category(){
        $category = Category::factory()->create();
        $response = $this->deleteJson('/api/v1/categories/'.$category->id);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
    public function test_category_validation()
    {
        $response = $this->postJson('/api/v1/categories', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);

    }
}
