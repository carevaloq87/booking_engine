<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use App\Models\User;
use App\Models\ServiceProvider;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ServiceProviderTest extends DuskTestCase
{

    /**
     * Test Create, Read Update and Delete a Service Provider
     *
     * @return void
     */
    public function testCRUDServiceProvider()
    {
        $this->browse(function ($browser) {
            $user = User::where('id', 69)->first();
            $browser->loginAs($user)
                    ->visit('/service_providers/create')
                    ->type('name', 'Test Dusk Service Provider')
                    ->type('contact_name', 'Test Dusk')
                    ->type('phone', str_repeat('4', 13))
                    ->type('email', 'sp@booking.com')
                    ->press('Add')
                    ->assertPathIs('/service_providers')
                    ->assertSee('Service Provider was successfully added!');
        });
        $this->browse(function ($browser) {
            $user = User::where('id', 69)->first();
            $browser->loginAs($user)
                    ->visit('/service_providers')
                    ->clickLink('›')
                    ->assertSee('Test Dusk Service Provider');
        });
        $this->browse(function ($browser) {
            $user = User::where('id', 69)->first();
            $service_provider = ServiceProvider::where('name', 'Test Dusk Service Provider')->first();
            $browser->loginAs($user)
                    ->visit("service_providers/show/".$service_provider->id)
                    ->assertSee('Test Dusk Service Provider');
        });

        $this->browse(function ($browser){
            $user = User::where('id', 69)->first();
            $service_provider = ServiceProvider::where('name', 'Test Dusk Service Provider')->first();
            $url = "/service_providers/".$service_provider->id. "/edit";
            $browser->loginAs($user)
                    ->visit($url)
                    ->type('name', 'Test Dusk Service Provider Modified')
                    ->type('contact_name', 'Test Dusk Modified')
                    ->type('phone', str_repeat('3', 13))
                    ->type('email', 'sp@dusk.com')
                    ->press('Update')
                    ->assertPathIs('/service_providers')
                    ->assertSee('Service Provider was successfully updated!');
                });


        $this->browse(function ($browser){
            $user = User::where('id', 69)->first();
            $service_provider = ServiceProvider::where('name', 'Test Dusk Service Provider Modified')->first();
            $browser->loginAs($user)
                    ->visit('/service_providers')
                    ->clickLink('›')
                    ->click('@delete-service-provider-'.$service_provider->id)
                    ->acceptDialog()
                    ->assertPathIs('/service_providers')
                    ->assertSee('Service Provider was successfully deleted!');
        });

    }


}
