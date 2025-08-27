<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => ""],
            ['name' => 'Admins', 'url' => "/admin"],
        ];
        $admins = User::role('admin')->orderBy('id','desc')->get();
        $user = null;

        return view('backend.admin.index', ['admins' => $admins, 'breadcrumbs' => $breadcrumbs, 'user'=>$user]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        
        $validatedData = $request->validate([
            'id'            => 'nullable|integer',
            'name'          => 'required|string|max:100',
            'email'         => 'required|email|unique:users,email,' . ($request->id ?? 'NULL'), 
            'contact'       => 'required|string|max:15',
            'country_code'  => 'nullable|string|max:5',
            'status'        => 'required|in:0,1'
        ]);

        // dd($validatedData);

        if(!$request['id']){
            
            // generate password
            $password = Str::random(8);
            $validatedData['password'] = bcrypt($password);
        }

        //get the role 
        $role = Role::where('name','admin')->first();

        DB::beginTransaction();

        try{

            $admin = User::updateOrCreate(
                [
                    'id' => $request['id'],

                ], 
                $validatedData
            );

            if ($role) {
                $admin->assignRole($role);
            }

            Db::commit();
            // dd($admin);

            $message = $request['id'] ? 'Admin details updated successfully' : 'Admin added successfully.';

            session()->flash('toast', [
                'type' => 'success',
                'message' => $message
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Admin details succeessfully added'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Error while storing user', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            session()->flash('toast', [
                'type'    => 'danger',
                'message' => 'Failed to save user. Please try again',
                'error'   => $e->getMessage()
            ]);


            return response()->json([
                'success'   => false,
                'message'   => 'Failed to save admin details. Please try again',
                'error'     => $e->getMessage()                
            ], 500);

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
        // dd($id);

        try{

            $user = User::findOrFail($id);

            //send other reuqired data
            // $admins = User::role('admin')->orderBy('id','desc')->get();

            $breadcrumbs = [
                ['name' => 'Home', 'url' => ""],
                ['name' => 'Admins', 'url' => "/admin"],
                ['name' => 'Edit', 'url' => ""],
            ];

            return response()->json([
                'success'     => true,
                'message'     => 'Edit request of admin fetch successfully.',
                'user'        => $user,
                // 'admins'      => $admins,
                'breadcrumbs' => $breadcrumbs
            ]);

        }catch(\Exception $e){

            session()->flash('toast',[
                'type'    => 'danger',
                'message' => 'Failed to get user details, Try again',
                'error'   => $e->getmessage()
            ]);
            
            return response()->json([

                'success'     => true,
                'message'     => 'Edit request of admin fetch successfully.',
                'error'       => $e->getMessage()  
                
            ],500);

        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{

            $admin = user::findOrFail($id);
            $admin->delete();
    
            session()->flash('toast', [
                'type'    => 'success',
                'message' => 'Admin removed successfully.'
            ]);
            
            return redirect()->route('admin.index');

        }catch(\Exception $e){

            session()->flash('toast', [
                'type'    => 'danger',
                'message' => 'Something went wrong, Please try Again.',
                'error'   => $e->getMessage()
            ]);

            return redirect()->route('admin.index');
            
        }
      
    }
}
