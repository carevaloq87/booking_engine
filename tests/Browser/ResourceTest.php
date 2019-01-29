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
        // Create
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
        // Read
        $this->browse(function ($browser) {
            $user = User::where('id', 30)->first();
            $browser->loginAs($user)
                    ->visit('/resources')
                    ->assertSee('Test Dusk Resource');

        });
        // Edit
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
        //Delete
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
    /**
     * Set days and hours unavailability for a resource
     *
     * @return void
     */
    public function testSetUnavailability()
    {
        // Create the resource
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
        // Set Days

        $this->browse(function ($browser) {
            $user = User::where('id', 30)->first();
            $resource =  Resource::where('name', 'Test Dusk Resource')->first();
            $browser->loginAs($user)
                    ->visit('/resources/show/'.$resource->id)
                    ->clickLink("Days")
                    ->pause(20000)
                    ->press('Current Year')
                    ->drag('#Jun-15', '#Oct-25')
                    ->press('Submit')
                    ->pause(5000)
                    ->assertSee($resource->name);
        });
        // Set Hours
        $this->browse(function ($browser) {
            $user = User::where('id', 30)->first();
            $resource =  Resource::where('name', 'Test Dusk Resource')->first();
            $browser->loginAs($user)
                    ->visit('/resources/show/'.$resource->id)
                    ->clickLink("Hours")
                    ->pause(20000)
                    ->press('#hour')
                    ->drag('#Wed-540', '#Fri-600')
                    ->press('Submit')
                    ->pause(5000)
                    ->assertSee($resource->name);
        });
        //Set AdHoc
        $this->browse(function ($browser) {
            $user = User::where('id', 30)->first();
            $resource =  Resource::where('name', 'Test Dusk Resource')->first();
            $browser->loginAs($user)
                    ->visit('/resources/show/'.$resource->id)
                    ->clickLink("Ad hoc")
                    ->pause(20000)
                    ->click('.vdp-datepicker__calendar-button')
                    ->click('.cell:not(.blank):not(.disabled).day')
                    ->pause(1000)
                    ->drag('#adhoc-540', '#adhoc-780')
                    ->pause(1000)
                    ->type('details', 'Test Dusk Resource adhoc Details')
                    ->press('Submit')
                    ->screenshot('unavailableAdHoc1')
                    ->assertSee($resource->name);
        });
        //Delete the resource.
        $this->browse(function ($browser){
            $resource =  Resource::where('name', 'Test Dusk Resource')->first();
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
