<?php

namespace Tests\Feature;


use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
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
    public function test_get_user()
    {
        // Arrange
        User::factory()->count(3)->create();

        // Act
        $response = $this->getJson('/api/v1/users');

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => true
            ])
            ->assertJsonCount(4, 'data');
    }
    public function test_create_user()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'user@gmail.com',
            'password' => 'password',
            'role' => 'admin',
        ];

        $response = $this->postJson('/api/v1/users', $data);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true
            ]) ->assertJsonPath('data.name', 'Test User');

        $this->assertDatabaseHas('users', [
            'name' => 'Test User'
        ]);
    }
    public function test_update_user()
    {
        // Arrange
        $user=User::factory()->create();
        $data = [
            'name' => 'Updated User',
            'email' => 'update@gmail.com',
            'password' => 'password',
            'role' => 'admin',
        ];

        // Act
        $response = $this->putJson('/api/v1/users/' . $user->id, $data);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => true
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated User'
        ]);
    }
    public function test_delete_user(){
        $user = User::factory()->create();
        $response = $this->deleteJson('/api/v1/users/'.$user->id);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'data' => 'user deleted successfully',
        ]);
    }
    public function test_user_validation()
    {
        $response = $this->postJson('/api/v1/users', []);

        $response->assertJsonValidationErrors([
            'name',
            'email',
            'password',
            'role'
        ]);

    }
}
