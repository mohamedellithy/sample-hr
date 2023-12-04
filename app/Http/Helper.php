<?php

use Carbon\Carbon;
use App\Models\EmployeePricesChange;

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
        return round($price,3).' '.' ريال';
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


function get_empolyee_price_by_month($employee,$month,$year){
    $employee_price = EmployeePricesChange::where([
        'employee_id' => $employee->id
    ])->whereMonth('change_date','=',$month)
    ->whereYear('change_date','=',$year)->first();

    if($employee_price == null):
        $employee_price = EmployeePricesChange::where([
            'employee_id' => $employee->id
        ])->whereMonth('change_date','>',$month)
        ->whereYear('change_date','=',$year)->orderBy('change_date','asc')->first();
    endif;

    if($employee_price):
        $employee_price = $employee_price->amount;
    else:
        $employee_price =  $employee->salary;
    endif;

    return $employee_price;
}

function get_resource_name($key){
    $types_resources = [
        'balance'           => 'الرصيد السابق',
        'bank_withdraw'     => 'سحب كاش من البنك',
        'outgoing_resource' => 'مصدر خارجي',
        'sales'             => 'مبيعات',
        'client_payments_sales' => 'تحصيل مدفوعات مبيعات عميل'
    ];

    return isset($types_resources[$key]) ? $types_resources[$key] : '-';
}


?>
