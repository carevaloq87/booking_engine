<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnavailableDays extends Model
{

    /**
     * Create a new unavailabledays instance.
     *
     * @return void
     */
	public function __construct()
	{
        $this->current_year = date('Y');
        $this->next_year = date('Y', strtotime('+1 year'));
    }
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'unavailable_days';

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


    /**
     * Get days by resource ID
     *
     * @param int Resource id
     * @return array Days previously selected in a resource
     */
    public function getDaysByResourceId($resource_id)
    {
        $resource = Resource::findOrFail($resource_id);        
        $unavailable_days = $resource->unavailableDays;
        $resource_dates = [];        
        foreach($unavailable_days as $date) {            
            $is_current_year = date('Y', strtotime($date->date)) == $this->current_year;
            if( $is_current_year ) {
                $resource_dates['selected_current'][] = date('M-d', strtotime($date->date));
            }
            else {
                $resource_dates['selected_next'][] = date('M-d', strtotime($date->date));
            }
        }        
        return $resource_dates;

    }    

}
