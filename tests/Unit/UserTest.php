<?php

namespace Tests\Unit;

use App\DTOs\SaveUserDTO;
use App\Models\User;
use App\Repositories\IUserRepository;
use App\Services\Admin\IUserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery\MockInterface;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_create_User_using_mocking()
    {
        $dto = new SaveUserDTO(name: 'peter',email: 'peter@gmail.com',password: '12345678',role: 'admin');

        $fakeUser = new User([
            'id' => 1,
            'name' => 'peter',
            'email'=>'peter@gmail.com',
            'password' => '12345678',
            'role' => 'admin'
        ]);

        $this->mock(IUserRepository::class, function (MockInterface $mock) use ($dto, $fakeUser) {
            $mock->shouldReceive('store')
                ->once()
                ->with($dto)
                ->andReturn($fakeUser);
        });

        $service = app(IUserService::class);
        $result = $service->store($dto);

        $this->assertEquals('peter', $result->name);
    }
    public function test_update_User()
    {
        $dto = new SaveUserDTO(name: 'peter eid',email: 'peter@gmail.com',password: '12345678',role: 'admin');

        $User = new User([
            'id' => 1,
            'name' => 'peter',
            'email'=>'peter@gmail.com',
            'password' => '12345678',
            'role' => 'admin'
        ]);


        $newUser = new User([
            'id' => 1,
            'name' => 'peter eid',
            'email'=> 'peter@gmail.com',
            'password' => '12345678',
            'role' => 'admin'
        ]);
        $this->mock(IUserRepository::class, function (MockInterface $mock) use ($dto, $User, $newUser) {
            $mock->shouldReceive('update')
                ->once()
                ->with($User,$dto)
                ->andReturn($newUser);
        });
        $service = app(IUserService::class);
        $result = $service->update($User,$dto);

        $this->assertEquals('peter eid', $result->name);

    }
    public function test_delete_User()
    {
        $fakeUser = new User([
            'id' => 1,
            'name' => 'peter',
            'email'=>'peter@gmail.com',
            'password' => '12345678',
            'role' => 'admin'
        ]);
        $this->mock(IUserRepository::class, function (MockInterface $mock) use ($fakeUser) {
            $mock->shouldReceive('delete')
                ->once()
                ->with($fakeUser)
                ->andReturn(true);
        });
        $service = app(IUserService::class);
        $result = $service->delete($fakeUser);
        $this->assertTrue($result);
    }
    public function test_find_User_by_id_throws_exception_if_not_found()
    {
        $invalidId = 999;

        $this->mock(IUserRepository::class, function (MockInterface $mock) use ($invalidId) {
            $mock->shouldReceive('findById')
                ->once()
                ->with($invalidId)
                ->andThrow(new ModelNotFoundException('User not found'));
        });

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('User not found');

        $service = app(IUserService::class);
        $service->findById($invalidId);
    }
}
