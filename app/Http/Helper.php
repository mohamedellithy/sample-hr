<?php

use Carbon\Carbon;
if (!function_exists('IsActiveOnlyIf')) {
    function IsActiveOnlyIf($routes = [])
    {
        if (count($routes) == 0) {
            return '';
        }

        $current_route = \Route::currentRouteName();

        if (in_array($current_route, $routes)):
            return 'active open';
        endif;

        return '';
    }
}



if(!function_exists('TrimLongText')){
    function TrimLongText($text,$length = 100){
        $text = trim(strip_tags($text));
        $text  = str_replace('&nbsp;', ' ', $text);
        return mb_substr($text,0,$length).' ... ';
    }
}

if(!function_exists('formate_price')) {
    function formate_price($price)
    {
        return round($price,3).' '.' ريال عماني';
    }
}

if(!function_exists('formate_time')) {
    function formate_time($time,$numb)
    {
            $result = explode('-',$time);
            $carbonTime = Carbon::parse( $result[$numb]);
            $time24HourFormat = $carbonTime->format('H:i');
            return $time24HourFormat;

    }
}

if(!function_exists('formate_time2')) {
    function formate_time2($time)
    {
        $carbonTime = Carbon::createFromFormat('H:i:s', $time);
        $time12HourFormat = $carbonTime->format('h:i A');
        return $time12HourFormat;

    }
}

?>
