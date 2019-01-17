<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use App\Models\User;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends DuskTestCase
{

    /**
     * Test Create, Read Update and Delete a user
     *
     * @return void
     */
    public function testCRUDUser()
    {
        $this->browse(function ($browser) {
            $user = User::where('id', 69)->first();
            $browser->loginAs($user)
                    ->visit('/users/create')
                    ->type('name', 'Test Dusk User')
                    ->type('email', 'user@dusk.com')
                    ->type('password', '123')
                    ->type('password_confirmation', '123')
                    ->select('roles','1')
                    ->select('service_provider_id', '2')
                    ->press('Add')
                    ->assertPathIs('/users')
                    ->assertSee('User was successfully added!');
        });
        $this->browse(function ($browser) {
            $user = User::where('id', 69)->first();
            $browser->loginAs($user)
                    ->visit('/users')
                    ->assertSee('Test Dusk User');
        });

        $this->browse(function ($browser) {
            $user = User::where('id', 69)->first();
            $user_dusk = User::where('name', 'Test Dusk User')->first();
            $browser->loginAs($user)
                    ->visit("/users/show/".$user_dusk->id)
                    ->assertSee('Test Dusk User');
        });

        $this->browse(function ($browser){
            $user = User::where('id', 69)->first();
            $user_dusk = User::where('name', 'Test Dusk User')->first();
            $url = "/users/".$user_dusk->id. "/edit";
            $browser->loginAs($user)
                    ->visit($url)
                    ->type('name', 'Test Dusk User Modified')
                    ->type('email', 'dusk@dusk.com')
                    ->press('Update')
                    ->assertPathIs('/users')
                    ->assertSee('User was successfully updated!');
            });

        $this->browse(function ($browser){
            $user = User::where('id', 69)->first();
            $user_dusk = User::where('name', 'Test Dusk User Modified')->first();
            $browser->loginAs($user)
                    ->visit('/users')
                    ->click('@delete-user-'.$user_dusk->id)
                    ->acceptDialog()
                    ->assertPathIs('/users')
                    ->assertSee('User was successfully deleted!');
        });
    }


}
