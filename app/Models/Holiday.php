<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'holidays';

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
                'description'
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
     * Get the holidays for the current and next year.
     *
     * @return void
     */
    public function getTwoYearDates()
    {
        $current_year =  date('Y-m-d',strtotime(date('Y-01-01')));
        $next_year = date('Y-m-d', strtotime('last day of december next year'));

        return Holiday::where('date', '>=', $current_year)
                    ->where('date', '<=', $next_year)->paginate(25);
    }

}
