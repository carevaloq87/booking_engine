<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Resource;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ResourceTest extends DuskTestCase
{

    /**
     * Test Create, Read, Update and Delete a resource
     *
     * @return void
     */
    public function testCRUDResource()
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

        $this->browse(function ($browser) {
            $user = User::where('id', 30)->first();
            $browser->loginAs($user)
                    ->visit('/resources')
                    ->assertSee('Test Dusk Resource');

        });

        $this->browse(function ($browser){
            $resource =  Resource::where('name', 'Test Dusk Resource')->first();
            $user = User::where('id', 30)->first();
            $url = "/resources/".$resource->id. "/edit";

            $browser->loginAs($user)
                    ->visit($url)
                    ->pause(5000)
                    ->type('name', 'Test Dusk Resource Modified')
                    ->type('phone', str_repeat('4', 13))
                    ->type('email', 'testdusk@2.com')
                    ->press('Update')
                    ->assertPathIs('/resources')
                    ->assertSee('Resource was successfully updated!');
        });

        $this->browse(function ($browser){
            $resource =  Resource::where('name', 'Test Dusk Resource Modified')->first();
            $user = User::where('id', 30)->first();

            $browser->loginAs($user)
                    ->visit('/resources')
                    ->click('@delete-resource-'.$resource->id)
                    ->acceptDialog()
                    ->assertPathIs('/resources')
                    ->assertSee('Resource was successfully deleted!');
        });
    }


}
