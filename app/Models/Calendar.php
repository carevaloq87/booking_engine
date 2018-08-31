<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use DateInterval;
use DatePeriod;

class Calendar extends Model
{
    protected $current_year;
    protected $next_year;
    protected $days;
    protected $months;
    protected $hour;
    protected $half_hour;

    /**
     * Create a new calendar instance.
     *
     * @return void
     */
	public function __construct()
	{
        $this->current_year = date('Y');
        $this->next_year = date('Y', strtotime('+1 year'));
        $this->days = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        $this->months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $this->hour = 60;
        $this->half_hour = 30;
	}

    /**
     * Generate Calendars by year
     *
     * @return void
     */
    public function generateCalendars()
    {
        $calendar_current_year = self::generateCalendarByYear($this->current_year);
        $calendar_next_year = self::generateCalendarByYear($this->next_year);
        return  [
                    'current_year' => $calendar_current_year,
                    'next_year' => $calendar_next_year
                ];
    }

    public function generateCalendarByYear($year)
    {
        $start_time = new DateTime("first day of January ". $year);
        $end_time = new DateTime("first day of January  ". ($year +1) );
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($start_time, $interval, $end_time);
        foreach ($period as $dt) {
            $day_number = $dt->format('d');
            $month_name = $dt->format('M');
            if( $day_number == 1 ) { //Check if is the first day of the month
                $day_pos = array_search( $dt->format('D') , $this->days); // Check the positon of day in array as we start a week on Monday
                for($j=0; $j< $day_pos; $j++) {
                    $list[$month_name][$j] = ""; //Add empty values while it find the week day to set the 1st day of the month
                }
            }
            $list[$month_name][$day_pos] = $dt->format('d');
            $day_pos++;
        }
        return $list;
    }

    public function getServiceDays()
    {
        //$sv_id = request('sv_id');
        $calendars = self::generateCalendars();
        $calendars['selected_current'] = self::generateCalendar();
        $calendars['selected_current_interpreter'] = self::generateCalendar();
        $calendars['selected_next'] = self::generateCalendar();
        $calendars['selected_next_interpreter'] = self::generateCalendar();
        return $calendars;
    }

    public function generateCalendar()
    {
        $calendar = [];
        $iterations = rand(1,10);
        $two_digits = '%02d';
        for($i = 1 ; $i <= $iterations; $i++) {
            $random_month = rand(0,11);
            $random_day = rand(1,31);
            $day = sprintf($two_digits, $random_day);
            $calendar[] = $this->months[$random_month] . '-' . $day;
        }
        return $calendar;
    }

    public function getServiceHours()
    {
        $schedule['regular'] = [
            'time_name' => 'half_hour',
            'time_lenght' => $this->half_hour,
            'days' => self::generateHour($this->half_hour)
        ];
        $schedule['interpreter'] = [
            'time_name' => 'hour',
            'time_lenght' => $this->hour,
            'days' => self::generateHour($this->hour)
        ];
        return $schedule;
    }

    public function generateHour($lenght)
    {
        $hours = [];
        $iterations = rand(1,10);
        for($i = 1 ; $i <= $iterations; $i++) {
            $week = rand(0,6);
            $day = rand(0,23) * $lenght;
            $hours[] = $this->days[$week] . '-' . $day;
        }
        return $hours;
    }

    /**
     * Save Availagle days by service
     *
     * @param array $data
     * @return void
     */
    public function saveDaysInService($data)
    {
        $service_id = $data['id'];
        AvailableDays::where('service_id', $service_id)->delete();
        $dates = $data['dates'];

        $this->insertDaysInService($service_id, $this->current_year, $dates['current'], 0);
        $this->insertDaysInService($service_id, $this->current_year, $dates['current_interpreter'], 1);
        $this->insertDaysInService($service_id, $this->next_year, $dates['next'], 0);
        $this->insertDaysInService($service_id, $this->next_year, $dates['next_interpreter'], 1);
    }

    /**
     * Insert Available days in the database
     *
     * @param int $service_id
     * @param int $selected_year
     * @param array $selected_days
     * @param boolean $is_interpreter
     * @return void
     */
    public function insertDaysInService($service_id, $selected_year, $selected_days, $is_interpreter)
    {
        $days_cy = array_map(
                                function($item) use ($service_id, $selected_year, $is_interpreter)
                                {
                                    return ['service_id'=> $service_id, 'date' => $selected_year .'-'. $item, 'is_interpreter' => $is_interpreter];
                                },
                                $selected_days
                            );
        AvailableDays::insert($days_cy);
    }

}