<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertSee("Booking");
    }



    public function testGetServices()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->make();

        $user->assignRole($role->id);
        $response = $this->actingAs($user, 'web')->get('/services');
        $this->assertAuthenticatedAs($user);
        $response->assertOk();
        $response->assertSee('Tech for Non-Tech');
    }


    public function testgetLoginAsApi()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
        ])->json('POST', '/login', ['email' => '1@1.com',
                                    'password' => '123',
                                    'remember_me'=> false]);
        $response->assertRedirect('/services');
    }



}
