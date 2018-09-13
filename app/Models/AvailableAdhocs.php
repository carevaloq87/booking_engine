<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailableAdhocs extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'available_adhocs';

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
                  'date',
                  'time_length',
                  'start_time',
                  'duration',
                  'is_interpreter',
                  'service_id'
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
     * Get the service for this model.
     */
    public function service()
    {
        return $this->belongsTo('App\Models\Service','service_id');
    }

    /**
     * Get future Adhocs by service ID
     *
     * @param int Service Id
     * @return array Adhocs set in a service
     */
    public function getAdhocsByServiceId($service_id)
    {
        return self::where('service_id','=', $service_id)->where('date', '>=', date('Y-m-d'))->get();
    }
}
