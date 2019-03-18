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
                    ->where('date', '<=', $next_year)
                    ->orderBy('date', 'asc')
                    ->paginate(25);
    }

    /**
     * Delete the available or unavailable date if is a holiday.
     *
     * @return void
     */
    public function deselectHolidays()
    {
        $current_year =  date('Y-m-d',strtotime(date('Y-01-01')));
        $next_year = date('Y-m-d', strtotime('last day of december next year'));
        $dates = [];

        $available_days = AvailableDays::where('available_date', '>=', $current_year)
                                        ->where('available_date', '<=', $next_year)
                                        ->get();
        $available_adhocs = AvailableAdhocs::where('date', '>=', $current_year)
                                            ->where('date', '<=', $next_year)
                                            ->get();
        $unavailable_days = UnavailableDays::where('date', '>=', $current_year)
                                            ->where('date', '<=', $next_year)
                                            ->get();
        $unavailable_adhocs = UnavailableAdhocs::where('date', '>=', $current_year)
                                                ->where('date', '<=', $next_year)
                                                ->get();
        $holidays = $this->getTwoYearDates();
        foreach ($holidays as $key => $holiday) {
            $dates[] = date('Y-m-d', strtotime($holiday->date));
        }

        foreach ($available_days as $key => $available_day) {
            if(in_array($available_day->available_date, $dates) ) {
                $available_day->delete();
            }
        }

        foreach ($available_adhocs as $key => $available_adhoc) {
            if(in_array($available_adhoc->date, $dates) ) {
                $available_adhoc->delete();
            }
        }

        foreach ($unavailable_days as $key => $unavailable_day) {
            if(in_array($unavailable_day->date, $dates) ) {
                $unavailable_day->delete();
            }
        }

        foreach ($unavailable_adhocs as $key => $unavailable_adhoc) {
            if(in_array($unavailable_adhoc->date, $dates) ) {
                $unavailable_adhoc->delete();
            }
        }
    }

}
