<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Service;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ServiceTest extends DuskTestCase
{

    /**
     * Test Create, Read Update and Delete a service
     *
     * @return void
     */
    public function testCRUDService()
    {
        $this->browse(function ($browser) {
            $user = User::where('id', 30)->first();
            $browser->loginAs($user)
                    ->visit('/services/create')
                    ->type('name', 'Test Dusk Service')
                    ->type('phone', str_repeat('4', 13))
                    ->type('duration', '15')
                    ->type('interpreter_duration','45')
                    ->press('Add')
                    ->assertPathIs('/services')
                    ->assertSee('Service was successfully added!');
        });

        $this->browse(function ($browser) {
            $user = User::where('id', 30)->first();
            $browser->loginAs($user)
                    ->visit('/services')
                    ->assertSee('Test Dusk Service');

        });

        $this->browse(function ($browser){
            $user = User::where('id', 30)->first();
            $service = Service::where('name', 'Test Dusk Service')->first();
            $url = "/services/".$service->id. "/edit";
            $browser->loginAs($user)
                    ->visit($url)
                    ->pause(5000)
                    ->type('name', 'Test Dusk Service Modified')
                    ->type('description', 'New Description')
                    ->type('duration', '30')
                    ->type('interpreter_duration','60')
                    ->press('Update')
                    ->assertPathIs('/services')
                    ->assertSee('Service was successfully updated!');
        });

        $this->browse(function ($browser){
            $user = User::where('id', 30)->first();
            $service = Service::where('name', 'Test Dusk Service Modified')->first();
            $browser->loginAs($user)
                    ->visit('/services')
                    ->click('@delete-service-'.$service->id)
                    ->acceptDialog()
                    ->assertPathIs('/services')
                    ->assertSee('Service was successfully deleted!');
        });

    }

    public function testSetAvailability()
    {
        // Create the user
        $this->browse(function ($browser) {
            $user = User::where('id', 30)->first();
            $browser->loginAs($user)
                    ->visit('/services/create')
                    ->type('name', 'Test Dusk Service')
                    ->type('phone', str_repeat('4', 13))
                    ->type('duration', '15')
                    ->type('interpreter_duration','45')
                    ->press('Add')
                    ->assertPathIs('/services')
                    ->assertSee('Service was successfully added!');
        });
        // Set Days

        $this->browse(function ($browser) {
            $user = User::where('id', 30)->first();
            $service =  Service::where('name', 'Test Dusk Service')->first();
            $browser->loginAs($user)
                    ->visit('/services/show/'.$service->id)
                    ->clickLink("Days")
                    ->pause(20000)
                    ->press('Current Year')
                    ->drag('#Jun-15', '#Oct-25')
                    ->screenshot('availableDay')
                    ->press('Submit')
                    ->pause(5000)
                    ->assertSee($service->name);
        });
        // Set Hours
        $this->browse(function ($browser) {
            $user = User::where('id', 30)->first();
            $service = Service::where('name', 'Test Dusk Service')->first();
            $browser->loginAs($user)
                    ->visit('/services/show/'.$service->id)
                    ->clickLink("Hours")
                    ->pause(20000)
                    ->press('#hour')
                    ->drag('#Wed-540', '#Fri-600')
                    ->screenshot('availableDayHour')
                    ->press('Submit')
                    ->pause(5000)
                    ->assertSee($service->name);
        });
        //Set AdHoc
        /*$this->browse(function ($browser) {
            $user = User::where('id', 30)->first();
            $resource =  Resource::where('name', 'Test Dusk Resource')->first();
            $browser->loginAs($user)
                    ->visit('/resources/show/'.$resource->id)
                    ->clickLink("Hours")
                    ->pause(20000)
                    ->drag('#Wed-540', '#Fri-600')
                    ->screenshot('unavailableHour')
                    ->press('Submit')
                    ->pause(5000)
                    ->assertSee($resource->name);
        });*/
        //Delete the resource.
        $this->browse(function ($browser){
            $service =  Service::where('name', 'Test Dusk Service')->first();
            $user = User::where('id', 30)->first();

            $browser->loginAs($user)
                    ->visit('/services')
                    ->click('@delete-service-'.$service->id)
                    ->acceptDialog()
                    ->assertPathIs('/services')
                    ->assertSee('Service was successfully deleted!');
        });
    }



}
