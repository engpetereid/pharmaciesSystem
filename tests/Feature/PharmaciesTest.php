<?php

namespace Tests\Feature;


use App\Models\User;
use Tests\TestCase;
use App\Models\Pharma;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PharmaciesTest extends TestCase
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
    public function test_get_pharmacy()
    {
        // Arrange
        Pharma::factory()->count(3)->create();

        // Act
        $response = $this->getJson('/api/v1/pharmacies');

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => true
            ])
            ->assertJsonCount(3, 'data.data');
    }
    public function test_create_pharmacy()
    {
        $user=User::factory()->create();
        $data = [
            'name' => 'Test Pharmacy',
            'user_id' => $user->id,
        ];

        $response = $this->postJson('/api/v1/pharmacies', $data);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true
            ]) ->assertJsonPath('data.name', 'Test Pharmacy');

        $this->assertDatabaseHas('pharmacies', [
            'name' => 'Test Pharmacy'
        ]);
    }
    public function test_update_pharmacy()
    {
        // Arrange
        $pharmacy = Pharma::factory()->create();
        $user=User::factory()->create();
        $data = [
            'name' => 'Updated Pharmacy',
            'user_id' => $user->id,
        ];

        // Act
        $response = $this->putJson('/api/v1/pharmacies/' . $pharmacy->id, $data);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => true
            ]);

        $this->assertDatabaseHas('pharmacies', [
            'id' => $pharmacy->id,
            'name' => 'Updated Pharmacy'
        ]);
    }
    public function test_delete_pharmacy(){
        $pharmacy = Pharma::factory()->create();
        $response = $this->deleteJson('/api/v1/pharmacies/'.$pharmacy->id);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'Pharmacy deleted successfully',
        ]);
    }
    public function test_pharmacy_validation()
    {
        $response = $this->postJson('/api/v1/pharmacies', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);

    }
}
