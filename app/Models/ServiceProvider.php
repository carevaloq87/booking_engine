<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Resource;
use App\Models\Service;
use Exception;

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

    /**
     * Validate if the given resources or services belong to the service provider
     *
     * @param Request $request
     * @return void
     */
    public static function validateServiceProvider($request)
    {
        if(($request['resources'] || $request['services'] ) && $request['service_provider_id']) {
            $objs = (isset($request['resources']) ? $request['resources'] : $request['services']);
            foreach ($objs as $key => $obj) {
                if(isset($request['resources'])) {
                    $type = 'resources';
                    $local_obj = Resource::findOrFail($obj);
                } else {
                    $type = 'services';
                    $local_obj = Service::findOrFail($obj);
                }
                if($local_obj->service_provider_id != $request['service_provider_id']) {
                    throw new Exception("Error. One or more " . $type . " do not belong to the selected service provider");
                }
            }
        }
    }

}
