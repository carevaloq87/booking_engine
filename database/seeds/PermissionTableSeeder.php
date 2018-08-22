<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $permissions = [
           'role-list',
           'role-create',
           'role-edit',
           'role-delete',
           'users-list',
           'users-create',
           'users-edit',
           'users-delete',
           'service-list',
           'service-create',
           'service-edit',
           'service-delete',
           'service_provider-list',
           'service_provider-create',
           'service_provider-edit',
           'service_provider-delete',
           'resource-list',
           'resource-create',
           'resource-edit',
           'resource-delete',
           'booking-list',
           'booking-create',
           'booking-edit',
           'booking-delete',
           'booking_status-list',
           'booking_status-create',
           'booking_status-edit',
           'booking_status-delete',
           'client-list',
           'client-create',
           'client-edit',
           'client-delete',
           'log-list',
           'log-create',
           'log-edit',
           'log-delete'
        ];


        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }
    }
}