<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

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
     * Get all the Resources that belongs to the current user by service provider and allow pagination
     *
     * @param Request $request query coming from front-end table
     * @return Collection
     */
    public static function getResourcesByUserServiceProviderTable($request)
    {
        $search_value = '%' . $request->search . '%';
        $query = Resource::prepareResourcesQuery($search_value);
        if(isset($request->column) && !is_null($request->column)){
            $column = $request->column;
            if($request->column == 'name'){
                $column = 'resources.name';
            }
            if($request->column == 'id'){
                $column = 'resources.id';
            }
            if($request->column == 'service_provider'){
                $column = 'service_providers.name';
            }
            $query->orderBy($column, $request->order);
        }
        $data = $query->paginate($request->per_page);
        return $data;
    }

    /**
     * Create query limiting resources of each service provider and fields
     *
     * @param String $search_value
     * @return DBQuery
     */
    public static function prepareResourcesQuery($search_value)
    {
        $query = DB::table('resources')
                    ->join('service_providers', function($join){
                            $join->on('resources.service_provider_id', '=', 'service_providers.id');
                            $user = auth()->user();
                            if(!$user->isAdmin()){
                                $join->where('service_providers.id', '=', $user->service_provider_id);
                            }
                    })
                    ->select(
                                Resource::getServicesFieldsToShow()
                            )
                    ->orWhere("resources.name",'LIKE', '%'.$search_value.'%')
                    ->orWhere("resources.phone",'LIKE', '%'.$search_value.'%')
                    ->orWhere("resources.email",'LIKE', '%'.$search_value.'%')
                    ->orWhere("service_providers.name",'LIKE', '%'.$search_value.'%');
        return $query;
    }

    /**
     * Get fields to be displayed in tables
     *
     * @return array
     */
    public static function getServicesFieldsToShow()
    {
        $fields = [
            'resources.id',
            'resources.name',
            'resources.phone',
            'resources.email',
        ];
        if(auth()->user()->isAdmin()){
            $fields[] = 'service_providers.name AS sp_name';
        }
        return $fields;
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
