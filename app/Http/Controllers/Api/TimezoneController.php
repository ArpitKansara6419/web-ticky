<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TimezoneController extends Controller
{
    public function index(Request $request)
    {
        $json = File::get(public_path('country.json'));
        $countries = collect(json_decode($json, true));
        $timezones = $countries->select('timezones', 'name', 'phone_code', 'iso2');

        if($request->get('phone_code') && $request->get('iso2'))
        {
            $timezones = $timezones->where('phone_code', $request->get('phone_code'))
                        ->where('iso2', $request->get('iso2'))
                        ->first();

            
        }

        if(empty($timezones))
        {
            return response()->json([
                'status' => false,
                'message' => 'Timezones are not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Notifications are not available.',
            'data' => $timezones
        ], 200);
    }
}
