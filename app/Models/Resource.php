<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'resources';

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
                  'phone',
                  'email',
                  'service_provider_id'
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
     * Get the serviceProvider for this model.
     */
    public function serviceProvider()
    {
        return $this->belongsTo('App\Models\ServiceProvider','service_provider_id');
    }

    /**
     * Get the services for this model.
     */
    public function services()
    {
        return $this->belongsToMany('App\Models\Service','served_by','resource_id','service_id');
    }

    /**
     * Get the unavailable days for this model.
     */
    public function unavailableDays()
    {
        return $this->hasMany('App\Models\UnavailableDays');
    }


    /**
     * Get the unavailable hours for this model.
     */
    public function unavailableHours()
    {
        return $this->hasMany('App\Models\UnavailableHours');
    }

     /**
     * Get the unavailable adhoc for this model.
     */
    public function unavailableAdhocs()
    {
        return $this->hasMany('App\Models\UnavailableAdhocs');
    }

    public function bookings()
    {
        return $this->hasMany('App\Models\Booking');
    }

    /**
     * Get all the Resources that belongs to the current user service provider
     *
     * @return Collection
     */
    public static function getResourcesByUserServiceProvider()
    {
        $user = auth()->user();

        if($user->isAdmin()){
            $resources = Resource::with('serviceprovider')->paginate(25);
        } else {
            $resources = Resource::with('serviceprovider')
                        ->where('service_provider_id', '=', $user->service_provider_id)
                        ->paginate(25);
        }

        return $resources;
    }
    /**
     * Get Resource by Service Provider id.
     *
     * @param int $id
     * @return void
     */
    public static function  getResourcesByServiceServiceProvider($service)
    {
        $resources =  Resource::with('serviceprovider')
                            ->where('service_provider_id',$service->service_provider_id)
                            ->paginate(25);
        return $resources;
    }


}
