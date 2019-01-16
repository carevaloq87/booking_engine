<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use App\Models\User;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ResourceTest extends DuskTestCase
{

    /**
     * Test get the list of resources
     *
     * @return void
     */
    public function testGetResources()
    {
        $this->browse(function ($browser) {
            $user = User::where('id', 30)->first();
            $browser->loginAs($user)
                    ->visit('/resources')
                    ->assertSee('Sebastian Currea');

        });
    }
    /**
     * Test Create a new resource
     *
     * @return void
     */
    public function testCreateResource()
    {
        $this->browse(function ($browser) {
            $user = User::where('id', 30)->first();
            $browser->loginAs($user)
                    ->visit('/resources/create')
                    ->type('name', 'Test Dusk Resource')
                    ->type('phone', str_repeat('4', 13))
                    ->type('email', 'testdusk@1.com')
                    ->press('Add')
                    ->assertPathIs('/resources')
                    ->assertSee('Resource was successfully added!');


        });

    }

    /**
     * Test Update Resource
     *
     * @return void
     */
    public function testUpdateResource()
    {
        $this->browse(function ($browser){
            $user = User::where('id', 30)->first();
            $browser->loginAs($user)
                    ->visit('/resources/4/edit')
                    ->pause(5000)
                    ->type('name', 'Sebastian C')
                    ->type('phone', str_repeat('4', 13))
                    ->type('email', 'sebastian.currea@1.com')
                    ->press('Update')
                    ->assertPathIs('/resources')
                    ->assertSee('Resource was successfully updated!');
        });
    }

    /**
     * Test Delete Resource
     *
     * @return void
     */
    public function testDeleteService()
    {
        $this->browse(function ($browser){
            $user = User::where('id', 30)->first();
            $browser->loginAs($user)
                    ->visit('/resources')
                    ->click('@delete-resource-31')
                    ->acceptDialog()
                    ->assertPathIs('/resources')
                    ->assertSee('Resource was successfully deleted!');
        });
    }

}
