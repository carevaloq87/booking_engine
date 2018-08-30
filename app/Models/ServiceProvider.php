<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'service_providers';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                            'name',
                            'contact_name',
                            'phone',
                            'email'
                        ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get All services by service Provider
     *
     * @return void
     */
    public function services()
    {
        return $this->hasMany('App\Models\Service');
    }

    /**
     * Get All resources by service Provider
     *
     * @return void
     */
    public function resources()
    {
        return $this->hasMany('App\Models\Resource');
    }

    /**
     * Retrieve the service providers available for the current user
     *
     * @return Collection of service providers
     */
    public static function getServideProvidersByCurrentUser()
    {
        if(auth()->user()->isAdmin()) {
            $serviceProviders = ServiceProvider::pluck('name','id')->all();
        } else {
            $serviceProviders = ServiceProvider::where('id', auth()->user()->service_provider_id)->pluck('name','id')->all();
        }
        return $serviceProviders;
    }

}
