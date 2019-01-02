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
     * @return array
     */
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
            $list[$month_name][$day_pos] = ['day'=>$dt->format('d'), 'holiday'=>self::isHoliday($year,$dt->format('m'),$dt->format('d'))];
            $day_pos++;
        }
        return $list;
    }

    /**
     * Get days by service id
     *
     * @param request $request
     * @return array
     */
    public function getServiceDays($request)
    {
        $service_id = $request->serviceId;
        $available_days = new AvailableDays();
        $calendars = $available_days->getDaysByServiceId($service_id); // Selected services
        $calendars['current_year'] = self::generateCalendarByYear($this->current_year); //Calendar structure current year
        $calendars['next_year'] = self::generateCalendarByYear($this->next_year); //Calendar structure next year
        return $calendars;
    }

    /**
     * Get resource days
     *
     * @param Request $request
     * @return void
     */
    public function getResourceDays($request)
    {
        $resource_id = $request->resourceId;
        $unavailable_days = new UnavailableDays();
        $calendars = $unavailable_days->getDaysByResourceId($resource_id); // Selected services
        $calendars['current_year'] = self::generateCalendarByYear($this->current_year); //Calendar structure current year
        $calendars['next_year'] = self::generateCalendarByYear($this->next_year); //Calendar structure next year
        return $calendars;
    }

    /**
     * Get hours by service id
     *
     * @param request $request
     * @return array
     */
    public function getServiceHours($request)
    {
        $service_id = $request->serviceId;
        $available_days = new AvailableHours();
        return $available_days->getHoursByServiceId($service_id);
    }

    /**
     * Get hours by resource id
     *
     * @param request $request
     * @return array
     */
    public function getResourceHours($request)
    {
        $resource_id = $request->resourceId;
        $unavailable_hours = new UnavailableHours();
        return $unavailable_hours->getHoursByResourceId($resource_id);
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
                                    return ['service_id'=> $service_id, 'available_date' => $selected_year .'-'. $item, 'is_interpreter' => $is_interpreter];
                                },
                                $selected_days
                            );
        AvailableDays::insert($days_cy);
    }

    /**
     * Save Availagle Hours by service
     *
     * @param array $data
     * @return void
     */
    public function saveHoursInService($data)
    {
        $service_id = $data['id'];
        AvailableHours::where('service_id', $service_id)->delete();
        $hours = $data['hours'];

        $this->insertHoursInService($service_id, $hours['regular'], 0);
        $this->insertHoursInService($service_id, $hours['interpreter'], 1);
    }


    /**
     * Save unavailable hours by resource
     *
     * @param array $data
     * @return void
     */
    public function saveHoursInResource($data)
    {
        $resource_id = $data['id'];
        UnavailableHours::where('resource_id', $resource_id)->delete();
        $hours = $data['hours'];

        $this->insertHoursInResource($resource_id, $hours['regular']);
    }


    /**
     * Insert unavailable hours in the database
     *
     * @param int $resource_id
     * @param array $selected_days
     * @return void
     */
    public function insertHoursInResource($resource_id, $selected_hours)
    {
        $time_name = $selected_hours['time_name'];
        $time_length = converHourToTextOrNumber($selected_hours['time_name']);
        $user = auth()->user();
        $days = array_map(
                            function($item) use ($resource_id, $time_length,$user)
                            {
                                $day_start = explode('-', $item);
                                return [
                                            'resource_id'=> $resource_id,
                                            'day_week' => $day_start[0],
                                            'length' => $time_length,
                                            'start_time' => $day_start[1],
                                            'created_by'=>$user->id,
                                            'created_at'=>date('Y-m-d H:i:s')
                                        ];
                            },
                            $selected_hours['days']
                        );
        UnavailableHours::insert($days);
    }

    /**
     * Insert Available hours in the database
     *
     * @param int $service_id
     * @param array $selected_days
     * @param boolean $is_interpreter
     * @return void
     */
    public function insertHoursInService($service_id, $selected_hours, $is_interpreter)
    {
        $time_name = $selected_hours['time_name'];
        $time_length = converHourToTextOrNumber($selected_hours['time_name']);
        $days = array_map(
                            function($item) use ($service_id, $time_length, $is_interpreter)
                            {
                                $day_start = explode('-', $item);
                                return [
                                            'service_id'=> $service_id,
                                            'day_week' => $day_start[0],
                                            'time_length' => $time_length,
                                            'start_time' => $day_start[1],
                                            'is_interpreter' => $is_interpreter
                                        ];
                            },
                            $selected_hours['days']
                        );
        AvailableHours::insert($days);
    }

    /**
     * Save unavailable days by resource
     *
     * @param array $data
     * @return void
     */
    public function saveDaysInResource($data)
    {
        $resource_id = $data['id'];
        UnavailableDays::where('resource_id', $resource_id)->delete();
        $dates = $data['dates'];

        $this->insertDaysInResource($resource_id, $this->current_year, $dates['current']);
        $this->insertDaysInResource($resource_id, $this->next_year, $dates['next']);
    }

	/**
     * Insert Available days in the database
     *
     * @param int $resource_id
     * @param int $selected_year
     * @param array $selected_days
     * @return void
     */
    public function insertDaysInResource($resource_id, $selected_year, $selected_days)
    {
        $user = auth()->user();
        $days_cy = array_map(
                                function($item) use ($resource_id, $selected_year, $user)
                                {
                                    return ['resource_id'=> $resource_id,
                                            'date' => $selected_year .'-'. $item,
                                            'created_by'=>$user->id,
                                            'created_at'=>date('Y-m-d H:i:s')];
                                },
                                $selected_days
                            );

        UnavailableDays::insert($days_cy);
    }

    /**
     * Save Adhoc Hours and Days by service
     *
     * @param array $data
     * @return void
     */
    public function saveAdhocInService($data)
    {
        $service_id = $data['id'];
        $hours = $data['hours'];
        $date = $hours['date'];
        AvailableAdhocs::where('service_id', $service_id)
                        ->where('date', $date)
                        ->delete();

        $this->insertAdhocInService($service_id, $date, $hours['interpreter'], 1);
        $this->insertAdhocInService($service_id, $date, $hours['regular'], 0);
    }

    /**
     * Save Adhoc Hours and Days by resource
     *
     * @param array $data
     * @return void
     */
    public function saveAdhocInResource($data)
    {
        $resource_id = $data['id'];
        $hours = $data['hours'];
        $date = $hours['date'];
        try{

            UnavailableAdhocs::where('resource_id', $resource_id)
                            ->where('date', $date)
                            ->delete();

            $this->insertAdhocInResource($resource_id, $date, $hours['regular']);
        } catch (Exception $exception) {
            return back()->withInput()
                        ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Insert Adhoc Hours and Days in the database
     *
     * @param int $service_id
     * @param string $date
     * @param array $selected_hours
     * @param boolean $is_interpreter
     * @return void
     */
    public function insertAdhocInService($service_id, $date, $selected_hours, $is_interpreter)
    {
        $time_name = $selected_hours['time_name'];
        $time_length = converHourToTextOrNumber($selected_hours['time_name']);
        $duration = $selected_hours['duration'];
        $days = array_map(
                            function($item) use ($service_id, $date, $duration, $time_length, $is_interpreter)
                            {
                                $day_start = explode('-', $item);
                                return [
                                            'service_id'    => $service_id,
                                            'date'          => $date,
                                            'time_length'   => $time_length,
                                            'start_time'    => $day_start[1],
                                            'duration'    => $duration,
                                            'is_interpreter' => $is_interpreter
                                        ];
                            },
                            $selected_hours['hours']
                        );

        AvailableAdhocs::insert($days);
    }

    /**
     * Get future adhoc appts by service id
     *
     * @param request $request
     * @return array
     */
    public function getFutureAdhocs($request)
    {
        $service_id = $request->serviceId;
        $available_adhocs = new AvailableAdhocs();
        $adhocs = $available_adhocs->getAvailableAdhocHoursByServiceId($service_id); // Selected services
        return $adhocs;
    }


    /**
     * Insert Adhoc Hours and Days in the database by resource
     *
     * @param int $resource
     * @param string $date
     * @param array $selected_hours
     * @return void
     */
    public function insertAdhocInResource($resource_id, $date, $selected_hours)
    {
        $time_name = $selected_hours['time_name'];
        $time_length = converHourToTextOrNumber($selected_hours['time_name']);
        $duration = $selected_hours['duration'];
        $details = $selected_hours['details'];
        $days = array_map(
                            function($item) use ($resource_id, $date, $duration, $time_length, $details)
                            {
                                $day_start = explode('-', $item);
                                return [
                                            'resource_id'   => $resource_id,
                                            'date'          => $date,
                                            'length'        => $time_length,
                                            'start_time'    => $day_start[1],
                                            'duration'      => $duration,
                                            'details'       => $details,
                                        ];
                            },
                            $selected_hours['hours']
                        );

        UnavailableAdhocs::insert($days);
    }

    /**
     * Get future adhoc appts by service id
     *
     * @param request $request
     * @return array
     */
    public function getFutureResourceAdhocs($request)
    {
        $resource_id = $request->resourceId;
        $unavailable_adhocs = new UnavailableAdhocs();
        $adhocs = $unavailable_adhocs->getUnavailableAdhocHoursByResourceId($resource_id); // Selected resource
        return $adhocs;
    }

    /**
     * Determine if the date is holiday
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @return boolean
     */
    private function isHoliday($year, $month, $day)
    {

        $date = $year.'-'.$month.'-'.$day;
        $holiday_obj = new Holiday();
        $holidays = $holiday_obj->getTwoYearDates();
        $holiday_dates = [];
        foreach ($holidays as $key => $holiday) {
            if($date == date('Y-m-d', strtotime($holiday->date))) {
                return true;
            }
        }
        return false;
    }
}