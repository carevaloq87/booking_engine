<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testViewLoginForm()
    {
        $response = $this->get('/login');
        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }


    public function testLoginTrue()
    {
        $credential = [
            'email' => '1@1.com',
            'password' => '123'
        ];
        $this->post('login',$credential)->assertRedirect('/services');
    }

    public function testIncorrectLogin()
    {
        $credential = [
            'email' => '1@1.com',
            'password' => '123456'
        ];
        $response = $this->post('login',$credential);

        $response->assertSessionHasErrors();

    }


}
