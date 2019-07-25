<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException as ValidationException;

use Exception;

class DashboardController extends Controller
{
    /**
     * Display The Wolcome Home with stats
     *
     * @return void
     */
    public function home()
    {
        $year = date("Y-m-d", strtotime('first day of January'));
        $last_monday =  date("Y-m-d", strtotime('monday this week'));//date('Y-m-d',time()+( 1 - date('w'))*24*3600);
        $today = date('Y-m-d');
        $bookings = [];
        $bookings['day'] = self::getBookingStatsDay($today);
        $bookings['week'] = self::getBookingStatsPeriod($last_monday);
        $bookings['year'] = self::getBookingStatsPeriod($year);
        $bookings['regular'] = self::getBookingPercentages(false);
        $bookings['interpreter'] =  self::getBookingPercentages(true);
        return view('welcome', compact('bookings'));
    }

    /**
     * Get number of bookings for a day
     *
     * @param date $period
     * @return void
     */
    private function getBookingStatsDay($day)
    {
        try {
            $bookings = Booking::whereDate('created_at', '=', $day)->get()
                                ->count();
            return $bookings;
        } catch (Exception $exception) {
            return "Error " . $exception->getMessage();
        }
    }
    /**
     * Get number of bookings for period
     *
     * @param date $period
     * @return void
     */
    private function getBookingStatsPeriod($start_day)
    {
        try {
            $bookings = Booking::whereDate('created_at', '>=', $start_day)->get()
                                ->count();
            return $bookings;
        } catch (Exception $exception) {
            return "Error " . $exception->getMessage();
        }
    }
    /**
     * Get Regular or Interpreter Percentages
     *
     * @param bool $is_interpreter
     * @return void
     */
    private function getBookingPercentages($is_interpreter)
    {
        try {
            $total = Booking::whereIn('is_interpreter', [0,1])->get()->count();
            $total_interpreter = 0;
            if ($is_interpreter){
                $total_interpreter = Booking::where('is_interpreter', 1)->get()->count();
            } else {
                $total_interpreter = Booking::where('is_interpreter', 0)->get()->count();
            }
            $percentage =round(($total_interpreter * 100)/$total, 2) ;
            return $percentage;
        } catch (Exception $exception) {
            return "Error " . $exception->getMessage();
        }
    }

}
