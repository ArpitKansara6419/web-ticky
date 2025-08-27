<?php

use Carbon\Carbon;

if (!function_exists('timezoneToUTC')) {
    /**
     * Convert datetime from given timezone to UTC
     *
     * @param string $datetime (e.g., "2024-01-15 14:30:00")
     * @param string $timezone (e.g., "Asia/Kolkata")
     * @return Carbon|null (UTC datetime)
     */
    function timezoneToUTC($datetime, $timezone = null)
    {
        try {
            if(!$timezone)
            {
                $timezone = 'Asia/Kolkata';
            }
            return Carbon::parse($datetime, $timezone)->utc();
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists('utcToTimezone')) {
    /**
     * Convert UTC datetime to given timezone
     *
     * @param string $datetime (UTC datetime, e.g., "2024-01-15 09:00:00")
     * @param string $timezone (e.g., "Asia/Kolkata")
     * @return Carbon|null (Localized datetime)
     */
    function utcToTimezone($datetime, $timezone = null)
    {
        try {
            if(!$timezone)
            {
                $timezone = 'Asia/Kolkata';
            }
            return Carbon::parse($datetime, 'UTC')->setTimezone($timezone);
        } catch (\Exception $e) {
            return null;
        }
    }
}