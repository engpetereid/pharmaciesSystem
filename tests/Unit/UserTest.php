<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use App\Services\Admin\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    use RefreshDatabase;
    public function test_create_user(): void
    {
        $service = new UserService();
        $user = User::factory()->create();
        $this->assertDatabaseHas('users',[
            'name'=>$user->name,
            'id'=>$user->id,
        ]);
    }
    public function test_update_user(): void
    {
        $service = new UserService();
        $user = User::factory()->create();
        $service ->update($user->id,[
            'name' => 'update User'
        ]);
        $this->assertDatabaseHas('users',[
            'name'=>'update User',
            'id'=>$user->id,
        ]);

    }
    public function test_delete_user(): void
    {
        $service = new UserService();
        $user = User::factory()->create();
        $service ->delete($user->id);
        $this->assertDatabaseMissing('users',[
            'name'=>$user->name,
            'id'=>$user->id,
        ]);
    }
}
