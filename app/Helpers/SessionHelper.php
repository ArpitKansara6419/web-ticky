<?php

namespace App\Helpers ;

use App\Models\Lead;
use Carbon\Carbon;
use Carbon\CarbonPeriod;



// Define a helper function to format the number
if (! function_exists('formatHumanNumber')) {
    function formatHumanNumber($number) {
        if ($number >= 1000 && $number < 1000000) {
            return number_format($number / 1000, 1) . 'k'; // For thousands
        } elseif ($number >= 1000000 && $number < 1000000000) {
            return number_format($number / 1000000, 1) . 'M'; // For millions
        } elseif ($number >= 1000000000) {
            return number_format($number / 1000000000, 1) . 'B'; // For billions
        }
        return number_format($number); // Default format for smaller numbers
    }
}

//DashBoard Helpers

function getLeads() {

   $confirmLeads = Lead::Where('lead_status','confirm')->count();
   $rescheduleLeads = Lead::Where('lead_status','reschedule')->count();
   $cancelLeads = Lead::Where('lead_status','cancelled')->count();

//    dd($rescheduleLeads);
    return [
        'confirmLeads'      => $confirmLeads,
        'rescheduleLeads' => $rescheduleLeads,
        'cancelLeads'  => $cancelLeads,
    ];
}