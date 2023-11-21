<?php

namespace App\Services;
use App\Models\User;
use App\Mail\VerifyCodeMail;
use Illuminate\Support\Facades\Mail;
class CalculateHourSalaryService
{

    public function calculateHourlyWage($monthlySalary,$hours = 10,$workDays = 30)
    {
        if ($workDays <= 0) {
            return null;
        }

        $hourlyWage = $monthlySalary / ($workDays * $hours);
        return round($hourlyWage,3);
    }

    public function calculateDayWage($monthlySalary,$days,$hours =10,$workDays = 30)
    {
        $calculations_days = 0;
        if($days > 20){
            $calculations_days = ($days + 4) * $hours; 
        } else {
            $calculations_days = $days * $hours; 
        }
        return round($this->calculateHourlyWage($monthlySalary) * $calculations_days,3);
    }


}
