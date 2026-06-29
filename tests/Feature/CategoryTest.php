<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    private function loginAsAdmin(): void
    {
        Sanctum::actingAs(
            User::factory()->create(['role' => 'admin'])
        );
    }

    public function test_get_categories()
    {
        $this->loginAsAdmin();
        Category::factory()->count(3)->create();

        $response = $this->getJson(route('api.categories.index'));

        $response->assertStatus(200)
            ->assertJson([
                'status' => true
            ])
            ->assertJsonCount(3, 'data');
    }
    public function test_create_category()
    {
        $this->loginAsAdmin();
        $data = [
            'name' => 'Test Category'
        ];

        $response = $this->postJson(route('api.categories.store'), $data);

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
        $this->loginAsAdmin();
        // Arrange
        $category = Category::factory()->create();

        $data = [
            'name' => 'Updated Category'
        ];

        // Act
        $response = $this->putJson(route('api.categories.update' , $category), $data);

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
        $this->loginAsAdmin();
        $category = Category::factory()->create();
        $response = $this->deleteJson(route('api.categories.destroy',$category->id));
        $response->assertStatus(200);
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
    public function test_category_validation()
    {
        $this->loginAsAdmin();
        $response = $this->postJson(route('api.categories.store'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);

    }
    public function test_guest_cannot_get_categories()
    {

        $response = $this->getJson(
            route('api.categories.index')
        );

        $response->assertUnauthorized();
    }
    public function test_non_admin_cannot_delete_category()
    {
        Sanctum::actingAs(
            User::factory()->create([
                'role' => 'supervisor'
            ])
        );

        $category = Category::factory()->create();

        $response = $this->deleteJson(
            route('api.categories.destroy', $category)
        );

        $response->assertForbidden();
    }
}
