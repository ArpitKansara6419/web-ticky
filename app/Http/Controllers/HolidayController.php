<?php

namespace App\Http\Controllers;

use App\DataTables\HolidayDataTable;
use Carbon\Carbon;
use App\Models\Holiday;
use App\Models\HolidaySync;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\CalendarificService;
class HolidayController extends Controller
{
    public $calendarificService;

    public function __construct(CalendarificService $calendarificService)
    {
        $this->calendarificService = $calendarificService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $breadcrumbs = [
            ['name' => 'Home', 'url' => ""],
            ['name' => 'Holiday', 'url' => "/holiday"],
        ];
        return view('backend.holiday.dataTable_list', [
            'breadcrumbs'        => $breadcrumbs
        ]);
    }

    /**
     * DataTable List
     *
     * @param HolidayDataTable $holidayDataTable
     * @return JsonResponse
     */
    public function dataTable(HolidayDataTable $holidayDataTable) : JsonResponse
    {
        return $holidayDataTable->ajax();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $breadcrumbs = [
            ['name' => 'Home', 'url' => "/holiday"],
            ['name' => 'Holiday', 'url' => "/holiday"],
            ['name' => 'Create', 'url' => ""],
        ];

        return view('backend.holiday.form',[                     
            'breadcrumbs'   => $breadcrumbs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate input data
            $validatedData = $request->validate([
                "id"     => 'nullable|integer|exists:holidays,id', // Fixed the table name
                "title"  => "required|string|max:60",
                "date"   => "required|date",
                "note"   => "required|string|max:60",
                "status" => "required|in:1,0",
                "country_name" => "required",
                "type" => "required",
            ]);
            $validatedData['date'] = Carbon::parse($validatedData['date'])->format('Y-m-d');
    
            // Start a database transaction
            DB::beginTransaction();
    
            // Update or create holiday record
            $holiday = Holiday::updateOrCreate(
                ['id' => $validatedData['id'] ?? null], // Use `id` from validated data
                $validatedData
            );
    
            DB::commit(); // Commit transaction
    
            // Success message
            $message = $holiday->wasRecentlyCreated 
                ? 'Holiday added successfully.' 
                : 'Holiday updated successfully.';
    
            return redirect()->route('holiday.index')->with('toast', [
                'type'    => 'success',
                'message' => $message
            ]);
    
        } catch (\Exception $e) {
            // Roll back transaction in case of errors
            DB::rollBack();
    
            // Error message
            session()->flash('toast', [
                'type'    => 'danger',
                'message' => 'Failed to save Holiday data. Please try again.',
                'error'   => $e->getMessage()
            ]);
    
            return redirect()->back()->withInput();
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        try {
           
            $holiday = Holiday::findOrFail($id);

            $breadcrumbs = [
                ['name' => 'Home', 'url' => "/holiday"],
                ['name' => 'Holiday', 'url' => "/holiday"],
                ['name' => 'Edit', 'url' => ""],
            ];
    
            return view('backend/holiday/form', [
                
                'holiday'       => $holiday,
                'breadcrumbs'   => $breadcrumbs,               

            ])->with('toast', [

                'type' => 'success',
                'message' => 'Holiday data fetched successfully'
                
            ]);
    

        } catch (\Exception $e) {
         
            return back()->with('toast', [
                'type'    => 'warning',
                'message' => 'Something went wrong while fetching Holiday Data, try again'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try{

            Holiday::findOrFail($id)->delete();
    
            // session()->flash('toast', [
            //     'type'    => 'success',
            //     'message' => 'Holiday Removed Successfully.'
            // ]);
            
            return response()->json([
                'status' => true,
                'message' => 'Holiday Removed Successfully.'
            ]);

        }catch(\Exception $e){

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.'
            ]);

            // session()->flash('toast', [
            //     'type'    => 'danger',
            //     'message' => 'Something went wrong, Please Try Again.',
            //     'error'   => $e->getMessage()
            // ]);

            // return redirect()->route('holiday.index');
            
        } 
    }

    /**
     * Get countries
     */
    public function getCountries(Request $request)
    {
        $response = $this->calendarificService->getCountries();

        $year = $request->get('year', null);

        $getAll = HolidaySync::where('year', $year)->get();

        foreach($response as $key => $value){
            $response[$key]['is_sync'] = $getAll->where('country_name', $value['country_name'])->count() > 0 ? true : false;
        }
        
        $html = view('backend.holiday.countries', compact('response', 'year'))->render();
        
        return response()->json([
            'status' => true,
            'html' => $html
        ]);
    }

    public function sync(Request $request)
    {
        try {
            DB::beginTransaction();
            $country = $request->get('country');
            $year = $request->get('year');
            $country_code = $request->get('country_code');
            $uuid = $request->get('uuid');
            $flag_unicode = $request->get('flag_unicode');

            // $response = $this->calendarificService->getHolidays($country, $year);
            $check = HolidaySync::where('country_name', $country)->where('year', $year)->count();
            if($check > 0){
                return response()->json([
                    'status' => false,
                    'message' => 'Holiday already synced.'
                ]);
            }

            $response = $this->calendarificService->getAllHolidays($country_code, $year);

            /**
             * FILTER
             */

            $sync = HolidaySync::create([
                'country_name' => $country,
                'iso_3166' => $country_code,
                'total_holidays' => 0,
                'supported_languages' => $country,
                'uuid' => $uuid,
                'flag_unicode' => $flag_unicode,
                'year' => $year,
            ]);
            
            foreach($response as $holiday)
            {
                $check = Holiday::where('date', $holiday['date']['iso'])
                        ->where('country_name', $country)
                        ->where('title', $holiday['name'])
                        ->count();
                if($check > 0){
                    continue;
                }
                
                $holiday_db = app(Holiday::class);
                $holiday_db->title = $holiday['name'];
                $holiday_db->date = $holiday['date']['iso'];
                
                $holiday_db->note = $holiday['description'];
                
                $holiday_db->country_name = $country;
                $holiday_db->country_code = $country_code;
                if(!empty($holiday['type'])){
                    $holiday_db->type = implode(',', $holiday['type']);
                }else{
                    $holiday_db->type = $holiday['type'];
                }
                $holiday_db->description = $holiday['description'];
                $holiday_db->primary_type = $holiday['primary_type'];
                $holiday_db->holiday_sync_id = $sync->id;
                
                if(in_array('National holiday', $holiday['type']))
                {
                    $holiday_db->status = 1;
                    $holiday_db->save();
                }else{
                    $holiday_db->status = 0;
                }
                
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Holiday Synced Successfully.',
                'response' => $response
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function activeInactive(Request $request, Holiday $holiday){
        try{
            if($holiday->status == 1){
                $holiday->status = 0;
            }else{
                $holiday->status = 1;
            }
            $holiday->save();
            return response()->json([
                'status' => true,
                'message' => 'Holiday Status Updated Successfully.'
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
