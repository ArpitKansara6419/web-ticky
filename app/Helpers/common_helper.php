<?php

use App\Models\CustomerPayable;
use App\Models\Engineer;
use App\Models\EngineerLeave;
use App\Models\Holiday;
use App\Models\MasterData;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use libphonenumber\PhoneNumberUtil;

if (! function_exists('pureMobileNo')) {
    function pureMobileNo($mobile_no, $iso2)
    {
        $phoneUtil = PhoneNumberUtil::getInstance();

        $phoneNumber = $phoneUtil->parse($mobile_no, strtoupper($iso2));

        $number = $phoneNumber->getNationalNumber();

        return $number;
    }
}
if (! function_exists('durationToSeconds')) {
    function durationToSeconds($duration)
    {
        $explode = explode(':', $duration);

        $hours = 00;
        if (isset($explode[0])) {
            $hours = (int)$explode[0];
        }
        $minutes = 00;
        if (isset($explode[1])) {
            $minutes = (int)$explode[1];
        }
        $seconds = 00;
        if (isset($explode[2])) {
            $seconds = (int)$explode[2];
        }
        return ($hours * 3600) + ($minutes * 60) + $seconds;
    }
}

if (! function_exists('secondsToDuration')) {
    function secondsToDuration($seconds)
    {
        $seconds = max(0, $seconds); // ensure non-negative
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
}


if (! function_exists('getActualWorkTime')) {
    function getActualWorkTime($total_work_time, $break_time)
    {
        // Convert to seconds
        $total_seconds = durationToSeconds($total_work_time);
        $break_seconds = durationToSeconds($break_time);

        // Subtract to get actual work time
        $actual_work_seconds = $total_seconds - $break_seconds;

        // Handle negative case (if break > work time)
        if ($actual_work_seconds < 0) {
            $actual_work_seconds = 0;
        }

        $actual_work_time = secondsToDuration($actual_work_seconds);

        return $actual_work_time;
    }
}


if (! function_exists('slugToString')) {
    function slugToString($slug)
    {
        return Str::of($slug)->replace('_', ' ')->title();
    }
}

if (! function_exists('fetchFromMasterDataByKey')) {
    function fetchFromMasterDataByKey($key)
    {
        $value = MasterData::where('key_name', $key)->first();

        if ($value) {
            return $value->label_name;
        }

        return $value;
    }
}

if (! function_exists('getPreviousMonthLeave')) {
    function getPreviousMonthLeave($engineerId, $leave_credited_this_month = null)
    {
        $previousMonthRemainingLeave = $leave_credited_this_month;

        $leaves = EngineerLeave::where('engineer_id', $engineerId)
            ->where('status', 'approved')
            ->where('leave_type', 'paid')
            ->where('leave_approve_status', 'approved')
            ->whereMonth('paid_from_date', now()->subMonth()->month)
            ->whereYear('paid_from_date', now()->subMonth()->year)
            ->get();

        $previousMonthLeave = 0.00;
        if ($leaves->isNotEmpty()) {
            $previousMonthLeave = $leaves->sum('paid_leave_days');
        }

        $engineer = Engineer::where('id', $engineerId)
            ->whereMonth('job_start_date', '>=', now()->subMonth()->month)
            ->whereYear('job_start_date', '>=', now()->subMonth()->year)
            ->first();



        if (!$engineer) {
            return number_format(0, 2);
        }

        return number_format($previousMonthRemainingLeave - $previousMonthLeave, 2);
    }
}

if (! function_exists('fetchTimezone')) {
    function fetchTimezone($timezone = null)
    {
        $result = [];
        if($timezone){
            $json = File::get(public_path('country.json'));
            $countries = collect(json_decode($json, true));
            $result = $countries->pluck('timezones')
                ->flatten(1)
                ->firstWhere('zoneName', $timezone);
        }
        
        return $result;
    }
}

if (! function_exists('checkHoliday')) {
    function checkHoliday($date)
    {
        $holidays = Holiday::where('date', $date)->first();

        if($holidays)
        {
            return true;
        }
        
        return false;
    }
}

if (! function_exists('checkCustomerPayable')) {
    function checkCustomerPayable($ticketWorkId)
    {
        $customer_payable = CustomerPayable::select('*')
        ->with('ticket')->where('ticket_work_id', $ticketWorkId)->first();

        if($customer_payable)
        {
            return true;
        }
        
        return false;
    }
}

if (! function_exists('getWorkingDaysOfCurrentMonth')) {
    function getWorkingDaysOfCurrentMonth($year, $month, $engineer = null): int
    {
        $start = Carbon::create($year, $month, 1);
        $end = $start->copy()->endOfMonth();

        $workingDays = 0;
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            if (!in_array($date->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
                $workingDays++;
            }
        }

        if($engineer)
        {
            $holiday = Holiday::where('country_name', $engineer['addr_country'])
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->count();
            
            $workingDays = $workingDays - $holiday;

            return $workingDays;
        }

        return $workingDays;
    }
}

