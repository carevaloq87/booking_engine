<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Holiday;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HolidayTest extends DuskTestCase
{

    /**
     * Test Create, Read Update and Delete a Service Provider
     *
     * @return void
     */
    public function testCRUDHoliday()
    {
        //Create
        $this->browse(function ($browser) {
            $user = User::where('id', 69)->first();
            $browser->loginAs($user)
                    ->visit('holidays/create')
                    ->click('.vdp-datepicker__calendar-button')
                    ->click('.cell:not(.blank):not(.disabled).day')
                    ->type('description', 'Test Dusk Holiday')
                    ->press('Add')
                    ->assertPathIs('/holidays')
                    ->assertSee('Holiday was successfully added!');
        });
        // Read
        $this->browse(function ($browser) {
            $user = User::where('id', 69)->first();
            $browser->loginAs($user)
                    ->visit('/holidays')
                    ->clickLink('›')
                    ->assertSee('Test Dusk Holiday');
        });

        $this->browse(function ($browser) {
            $user = User::where('id', 69)->first();
            $holiday = Holiday::where('description', 'Test Dusk Holiday')->first();
            $browser->loginAs($user)
                    ->visit("holidays/show/".$holiday->id)
                    ->assertSee($holiday->description);
        });

        $this->browse(function ($browser){
            $user = User::where('id', 69)->first();
            $holiday = Holiday::where('description', 'Test Dusk Holiday')->first();
            $url = "/holidays/".$holiday->id. "/edit";
            $browser->loginAs($user)
                    ->visit($url)
                    ->type('description', 'Test Dusk Holiday Modified')
                    ->press('Update')
                    ->assertPathIs('/holidays')
                    ->assertSee('Holiday was successfully updated!');
                });


        $this->browse(function ($browser){
            $user = User::where('id', 69)->first();
            $holiday = Holiday::where('description', 'Test Dusk Holiday Modified')->first();
            $browser->loginAs($user)
                    ->visit('/holidays')
                    ->clickLink('›')
                    ->click('@delete-service-provider-'.$holiday->id)
                    ->acceptDialog()
                    ->assertPathIs('/holidays')
                    ->assertSee('Holiday was successfully deleted!');
        });

    }


}
