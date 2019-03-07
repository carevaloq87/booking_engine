<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ServiceProvider;
use DB;

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
                            'interpreter_duration',
                            'listed_interpreter_duration',
                            'spaces',
                            'service_provider_id',
                            'color'
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
     * Get the available days for this model.
     */
    public function availableDays()
    {
        return $this->hasMany('App\Models\AvailableDays');
    }

    /**
     * Get the available hours for this model.
     */
    public function availableHours()
    {
        return $this->hasMany('App\Models\AvailableHours');
    }

    /**
     * Get the adhoc hours and days for this model.
     */
    public function AvailableAdhocs()
    {
        return $this->hasMany('App\Models\AvailableAdhocs');
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
            $services = Service::with('serviceprovider')
                        ->where('service_provider_id', '=', $user->service_provider_id)
                        ->paginate(25);
        }
        return $services;
    }

    /**
     * Get all the Services that belongs to the current user by service provider and allow pagination
     *
     * @param Request $request query coming from front-end table
     * @return Collection
     */
    public static function getServicesByUserServiceProviderTable($request)
    {
        $search_value = '%' . $request->search . '%';
        $query = Service::prepareServicesQuery($search_value);
        if(isset($request->column) && !is_null($request->column)){
            $column = $request->column;
            if($request->column == 'name'){
                $column = 'services.name';
            }
            if($request->column == 'id'){
                $column = 'services.id';
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
     * Create query limiting services of each service provider and fields
     *
     * @param String $search_value
     * @return DBQuery
     */
    public static function prepareServicesQuery($search_value)
    {
        $query = DB::table('services')
                    ->join('service_providers', function($join){
                            $join->on('services.service_provider_id', '=', 'service_providers.id');
                            $user = auth()->user();
                            if(!$user->isAdmin()){
                                $join->where('service_providers.id', '=', $user->service_provider_id);
                            }
                    })
                    ->select(
                                Service::getServicesFieldsToShow()
                            )
                    ->orWhere("services.name",'LIKE', '%'.$search_value.'%')
                    ->orWhere("services.duration",'LIKE', '%'.$search_value.'%')
                    ->orWhere("services.interpreter_duration",'LIKE', '%'.$search_value.'%')
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
            'services.id',
            'services.name',
            'services.duration',
            'services.interpreter_duration'
        ];
        if(auth()->user()->isAdmin()){
            $fields[] = 'service_providers.name AS sp_name';
        }
        return $fields;
    }

    /**
     * Get Service by Service Provider id.
     *
     * @param int $id
     * @return void
     */
    public static function  getServicesByResourceServiceProvider($resource)
    {
        $services =  Service::with('serviceprovider')
                            ->where('service_provider_id',$resource->service_provider_id)
                            ->paginate(25);
        return $services;
    }

}
