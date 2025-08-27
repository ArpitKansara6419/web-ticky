<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class CalendarificService
{
    protected $apiKey = 'A6TKQKDxfHB4IQkVM5IXgpuuN3Rj6ulw';

    protected $baseUrl = 'https://calendarific.com/api/v2/';

    public function __construct() {
        
    }

    /**
     * Get Countries
     *
     * @return mixed
     */
    public function getCountries() : mixed
    {
        try {
            $url = $this->baseUrl . 'countries?api_key=' . $this->apiKey;

            /**
             * Make HttpClient call to get the response
             */
            $response = Http::get($url)->json();

            /**
             * Sync Countries with the tables
             *
             *   "country_name" => "Afghanistan"
             *   "iso-3166" => "AF"
             *   "total_holidays" => 24
             *   "supported_languages" => 2
             *   "uuid" => "f0357a3f154bc2ffe2bff55055457068"
             *   "flag_unicode" => "🇦🇫"
             */
            return $response['response']['countries'];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    
    public function getAllHolidays($countryCode, $year) : mixed
    {
        try {
            $url = $this->baseUrl . 'holidays?api_key=' . $this->apiKey . '&country=' . $countryCode . '&year=' . $year;

            $response = Http::get($url)->json();

            return $response['response']['holidays'];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
