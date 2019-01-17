<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Role;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RoleTest extends DuskTestCase
{

    /**
     * Test Create, Read Update and Delete a Role
     *
     * @return void
     */
    public function testCRUDRole()
    {
        $this->browse(function ($browser) {
            $user = User::where('id', 69)->first();
            $browser->loginAs($user)
                    ->visit('/roles/create')
                    ->type('name', 'Test Dusk Role')
                    ->check('permission[]')
                    ->press('Submit')
                    ->assertPathIs('/roles')
                    ->assertSee('Role was successfully added!');
        });
        $this->browse(function ($browser) {
            $user = User::where('id', 69)->first();
            $browser->loginAs($user)
                    ->visit('/roles')
                    ->assertSee('Test Dusk Role');
        });

        $this->browse(function ($browser) {
            $user = User::where('id', 69)->first();
            $role = Role::where('name', 'Test Dusk Role')->first();
            $browser->loginAs($user)
                    ->visit("/roles/show/".$role->id)
                    ->assertSee('Test Dusk Role');
        });

        $this->browse(function ($browser){
            $user = User::where('id', 69)->first();
            $role = Role::where('name', 'Test Dusk Role')->first();
            $url = "/roles/".$role->id. "/edit";
            $browser->loginAs($user)
                    ->visit($url)
                    ->type('name', 'Test Dusk Role Modified')
                    ->press('Submit')
                    ->assertPathIs('/roles')
                    ->assertSee('Role was successfully updated!');
                });


        $this->browse(function ($browser){
            $user = User::where('id', 69)->first();
            $role = Role::where('name', 'Test Dusk Role Modified')->first();
            $browser->loginAs($user)
                    ->visit('/roles')
                    ->click('@delete-role-'.$role->id)
                    ->acceptDialog()
                    ->assertPathIs('/roles')
                    ->assertSee('Role was successfully deleted!');
        });

    }


}
