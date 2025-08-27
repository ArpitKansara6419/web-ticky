<?php

namespace App\Http\Controllers;

use App\Models\MasterData;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => ""],
            ['name' => 'Master Data', 'url' => "/master"],
        ];

        $masterData = MasterData::orderBy('id', 'desc')->get();
        return view('backend.master_data.index', [
            'masterData'         => $masterData, 
            'breadcrumbs'        => $breadcrumbs
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => "/master"],
            ['name' => 'Master Data', 'url' => "/master"],
            ['name' => 'Create', 'url' => ""],
        ];

        return view('backend.master_data.form',[                     
            'breadcrumbs'   => $breadcrumbs
        ]);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->toArray());

        try{

            $validatedData = $request->validate([
                "id"            => 'nullable|integer|exists:master_data,id',
                "key_name"      => "required|string|max:60",
                "label_name"    => "required|string|max:60",
                "type"          => "required|string|max:60",
                "extra"         => "nullable|json",            
                "status"        => "required|in:1,0"
            ]);

            DB::beginTransaction();
            
            $master = MasterData::updateOrCreate([
                'id' => $request['id']
            ], $validatedData);

            DB::commit();

            $message = $request['id'] ? 'Master-Data updated successfully' : 'Master-Data added successfully.';

            return redirect()->route('master.index')->with('toast', [
                    'type'    => 'success',
                    'message' => $message
            ]);

        }catch(Exception $e){

            DB::rollBack();


            session()->flash('toast', [
                'type'    => 'danger',
                'message' => 'Failed to save master data. Please try again',
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
        try {
           
            $masterD = MasterData::findOrFail($id);

            $breadcrumbs = [
                ['name' => 'Home', 'url' => "/master"],
                ['name' => 'Master Data', 'url' => "/master"],
                ['name' => 'Edit', 'url' => ""],
            ];
    
            return view('backend/master_data/form', [
                
                'masterD'       => $masterD,
                'breadcrumbs'   => $breadcrumbs,               

            ])->with('toast', [

                'type' => 'success',
                'message' => 'Ticket data fetched successfully'
                
            ]);
    

        } catch (\Exception $e) {
         
            return back()->with('toast', [
                'type'    => 'warning',
                'message' => 'Something went wrong while fetching master data, try again'
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
        try{

            MasterData::findOrFail($id)->delete();
    
            session()->flash('toast', [
                'type'    => 'success',
                'message' => 'Master Data Removed Successfully.'
            ]);
            
            return redirect()->route('master.index');

        }catch(\Exception $e){

            session()->flash('toast', [
                'type'    => 'danger',
                'message' => 'Something went wrong, Please Try Again.',
                'error'   => $e->getMessage()
            ]);

            return redirect()->route('master.index');
            
        }        
    }
}
