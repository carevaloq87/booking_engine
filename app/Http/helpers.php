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
