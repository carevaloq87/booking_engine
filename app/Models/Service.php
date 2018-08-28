<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class service extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'services';

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
                            'description',
                            'duration',
                            'listed_duration',
                            'spaces',
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
     * Get the resources for this model.
     */
    public function resources()
    {
        return $this->belongsToMany('App\Models\Resource','served_by','service_id','resource_id');
    }

    /**
     * Get all the Services that belongs to the current user service provider
     *
     * @return Collection
     */
    public static function getServicesByUserServiceProvider()
    {
        $user = auth()->user();
        if($user->isAdmin()){
            $services = Service::with('serviceprovider')->paginate(25);
        } else {
            $services = Service::with('serviceprovider')->where('service_provider_id', '=', $user->service_provider_id)->paginate(25);
        }
        return $services;
    }

}
