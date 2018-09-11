<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnavailableAdhocs extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'unavailable_adhocs';

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
                  'details',
                  'duration',
                  'type',
                  'resource_id'
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
     * Get the resource for this model.
     */
    public function resource()
    {
        return $this->belongsTo('App\Models\Resource','resource_id');
    }


}
