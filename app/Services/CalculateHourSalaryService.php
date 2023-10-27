<?php

namespace App\Services;
use App\Models\User;
use App\Mail\VerifyCodeMail;
use Illuminate\Support\Facades\Mail;
class CalculateHourSalaryService
{

    public function calculateHourlyWage($monthlySalary,$hours =8,$workDays = 30)
    {
        if ($workDays <= 0) {
            return null;
        }

        $hourlyWage = $monthlySalary / ($workDays * $hours);
        return round($hourlyWage);
    }


}
