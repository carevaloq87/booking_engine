<?php

if (! function_exists('getUserServiceProviderId')) {
    function getUserServiceProviderId()
    {
        if( Auth::check() )	{
    		return Auth::user()->service_provider_id ;
    	} else {
            return '';
        }
    }
}

if (! function_exists('getUserRoleName')) {
    function getUserRoleName()
    {
        if( Auth::check() ) {
            return Auth::user()->roles()->first()->name ;
        } else {
            return 'Anonymous';
        }
    }
}


    /**
     * Transform time in minutes to Hours and minutes on plain text
     *
     * @param int $value Minutes
     * @param int $duration Duraton of the appt
     * @return string
     */
if (! function_exists('valueToHour')) {
    function valueToHour($value, $duration)
    {
        $valute_in_hours = $value/60;
        $hour = sprintf("%02d", floor($valute_in_hours) );
        $minute = sprintf("%02d", round(fmod($valute_in_hours, 1) * 60));

        $finish_time = ($value + $duration) / 60;
        $finish_hour = sprintf("%02d",  floor($finish_time) );
        $finish_minute = sprintf("%02d", round(fmod($finish_time, 1) * 60));

        return "$hour:$minute - $finish_hour:$finish_minute";
    }
}

    /**
     * Convert text or numbers to hours in text or numbers
     *
     * @param string $hour
     * @return string int or string with hour
     */
if (! function_exists('converHourToTextOrNumber')) {
    function converHourToTextOrNumber($hour)
    {
        $hours = [
            'quarter_hour' => 15,
            'half_hour' => 30,
            'hour' => 60,
            15 => 'quarter_hour',
            30 => 'half_hour',
            60 => 'hour',
        ];
        return $hours[$hour];
    }
}
