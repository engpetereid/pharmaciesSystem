<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Tests\TestCase;
use App\Models\Drug;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DrugTest extends TestCase
{
    use RefreshDatabase;
    private function loginAsAdmin(): void
    {
        Sanctum::actingAs(
            User::factory()->create(['role' => 'admin'])
        );
    }

    public function test_get_drugs()
    {
        $this->loginAsAdmin();
        // Arrange
        Drug::factory()->count(3)->create();

        // Act
        $response = $this->getJson(route('api.drugs.index'));

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => true
            ])
            ->assertJsonCount(3, 'data');
    }
    public function test_create_Drug()
    {
        $this->loginAsAdmin();
        $category = Category::factory()->create();
        $data = [
            'name' => 'Test Drug',
            'price' => '200',
            'category_id' => $category->id,
        ];

        $response = $this->postJson(route('api.drugs.store'), $data);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true
            ]) ->assertJsonPath('data.name', 'Test Drug');

        $this->assertDatabaseHas('drugs', [
            'name' => 'Test Drug',
            'price' => '200',
            'category_id' => $category->id,
        ]);
    }
    public function test_update_drug()
    {
        $this->loginAsAdmin();

        $Drug = Drug::factory()->create();
        $newCategory = Category::factory()->create();

        $data = [
            'name' => 'Updated Drug',
            'price' => '300',
            'category_id' => $newCategory->id,
        ];


        $response = $this->putJson(route('api.drugs.update' , $Drug), $data);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true
            ]);

        $this->assertDatabaseHas('drugs', [
            'id' => $Drug->id,
            'name' => 'Updated Drug',
            'price' => '300',
            'category_id' => $newCategory->id,
        ]);
    }
    public function test_delete_drug(){
        $this->loginAsAdmin();
        $Drug = Drug::factory()->create();
        $response = $this->deleteJson(route('api.drugs.destroy',$Drug->id));
        $response->assertStatus(200);
        $this->assertDatabaseMissing('drugs', [
            'id' => $Drug->id,
        ]);
    }
    public function test_drug_validation()
    {
        $this->loginAsAdmin();
        $response = $this->postJson(route('api.drugs.store'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'price',
                'category_id',
            ]);

    }
    public function test_guest_cannot_get_drugs()
    {

        $response = $this->getJson(
            route('api.drugs.index')
        );

        $response->assertUnauthorized();
    }
    public function test_non_admin_cannot_delete_drug()
    {
        Sanctum::actingAs(
            User::factory()->create([
                'role' => 'supervisor'
            ])
        );

        $Drug = Drug::factory()->create();

        $response = $this->deleteJson(
            route('api.drugs.destroy', $Drug)
        );

        $response->assertForbidden();
    }
}
