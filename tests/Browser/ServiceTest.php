<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use App\Models\User;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ServiceTest extends DuskTestCase
{
    
    /**
     * Test get the list of services
     *
     * @return void
     */
    public function testGetServices()
    {
        $this->browse(function ($browser) {
            $user = User::where('id', 30)->first();
            $browser->loginAs($user)
                    ->visit('/services')
                    ->assertSee('Tech for Non-Tech');

        });
    }
    /**
     * Test Create a new service
     *
     * @return void
     */
    public function testCreateService()
    {
        $this->browse(function ($browser) {
            $user = User::where('id', 30)->first();
            $browser->loginAs($user)
                    ->visit('/services/create')
                    ->type('name', 'Test Dusk Service')
                    ->type('phone', str_repeat('4', 13))
                    ->type('duration', '30')
                    ->type('interpreter_duration','60')
                    ->press('Add')
                    ->assertPathIs('/services')
                    ->assertSee('Service was successfully added!');


        });

    }

    /**
     * Test Update Service
     *
     * @return void
     */
    public function testUpdateService()
    {
        $this->browse(function ($browser){
            $user = User::where('id', 30)->first();
            $browser->loginAs($user)
                    ->visit('/services/12/edit')
                    ->type('description', 'New Description')
                    ->type('duration', '30')
                    ->type('interpreter_duration','60')
                    ->press('Update')
                    ->assertPathIs('/services')
                    ->assertSee('Service was successfully updated!');
        });
    }

    /**
     * Test Delete Service
     *
     * @return void
     */
    public function testDeleteService()
    {
        $this->browse(function ($browser){
            $user = User::where('id', 30)->first();
            $browser->loginAs($user)
                    ->visit('/services')
                    ->click('@delete-service-45')
                    ->acceptDialog()
                    ->assertPathIs('/services')
                    ->assertSee('Service was successfully deleted!');
        });
    }

}
