<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use App\Models\User;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends DuskTestCase
{

    public function testWithNoLogin() {
        $this->browse(function ($browser) {

            $result = $browser->visit('/services')
                                ->assertPathIs('/login');
        });
    }

    public function testWrongCredentials() {
        $user = factory(User::class)->make([
            'email' => '1@1.com',
            'password' => '123465'
        ]);

        $this->browse(function ($browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', $user->password)
                    ->press('Login')
                    ->assertSee('These credentials do not match our records.');
        });
    }


    public function testLogin()
    {
        $user = factory(User::class)->make([
            'email' => '1@1.com',
            'password' => '123'
        ]);

        $this->browse(function ($browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', $user->password)
                    ->press('Login')
                    ->assertSee('Services');
        });


    }
}
