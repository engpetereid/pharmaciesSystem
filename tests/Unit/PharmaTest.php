<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use App\Services\Admin\PharmaService;
use App\Models\Pharma;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PharmaTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    use RefreshDatabase;
    public function test_create_pharma(): void
    {
        $service = new PharmaService();
        $user = User::factory()->create();
        $service->create([
            'name'=>'Pharma',
            'user_id'=>$user->id,
        ]);
        $this->assertDatabaseHas('pharmacies',[
            'name'=>'Pharma',
            'user_id'=>$user->id,
        ]);
    }
    public function test_update_pharma(): void
    {
        $service = new PharmaService();
        $user = User::factory()->create();
        $Pharma = $service->create([
            'name'=>'Pharma',
            'user_id'=>$user->id,

        ]);
        $service ->update($Pharma->id,[
            'name' => 'update Pharma'
        ]);
        $this->assertDatabaseHas('pharmacies',[
            'name'=>'update Pharma',
        ]);

    }
    public function test_delete_pharma(): void
    {
        $service = new PharmaService();
        $user = User::factory()->create();
        $Pharma = $service->create([
            'name'=>'Pharma',
            'user_id'=>$user->id,

        ]);
        $service ->delete($Pharma->id);
        $this->assertDatabaseMissing('pharmacies',[
            'name'=>'Pharma',
        ]);
    }
}
