<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Engineer;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class EngineerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        dd('f reach');
    }

    /**
     * Store a newly created resource in storage.
     */

     public function register(Request $request)
     {
        // dd('Reached here');
     
        try {

             $validatedData = $request->validate([
                 'name'          => 'nullable|string',
                 'first_name'    => 'required|string',
                 'last_name'     => 'required|string',
                 'email'         => 'required|email|unique:users,email',
                 'password'      => 'required|min:8',
                 'country_code'  => 'nullable|string',
                 'contact'       => 'required|string|unique:users,contact',
                 'profile_image' => 'nullable|string',
                 'is_system'     => 'nullable|boolean',
            ]);
     
            $validatedData['name'] = $validatedData['first_name'] . ' ' . $validatedData['last_name'];
            $validatedData['user_name'] = explode('@', $validatedData['email'])[0];
            $validatedData['status'] = 1;
            $validatedData['email_verification'] = 1;
            $validatedData['admin_verification'] = 1;
            $validatedData['phone_verification'] = 0;
     
            // Encrypt password
            $validatedData['password'] = bcrypt($validatedData['password']);
     
            DB::beginTransaction();
    
            $user = User::create($validatedData);
     
            $role = Role::where('name', 'engineer')->first();

            if ($role) {
                $user->assignRole($role);
            }
     
            //associated Engineer Table entry
            $validatedEnggData = ['user_id' => $user->id];
            Engineer::create($validatedEnggData);
            $token  = $user->createToken('authToken')->plainTextToken;
     
            DB::commit();
     
            return response()->json([
                'user'  => $user,
                'token' => $token,
                'role'  => $user->roles[0]->name
            ], 200);
     
        } catch (Exception $e) {
             
            DB::rollBack();
            
            // Return error response
            return response()->json([
                'message' => 'Error Occurred',
                'error' => $e->getMessage()
            ], 500);
        }
     }
     

    public function store(Request $request)
    {
        try {
           
            $validatedEnggData = $request->validate([
                'id'                => 'required|integer|exists:engineers,id',
                'about'             => 'nullable|string',
                'gender'            => 'nullable|in:male,female,other',
                'photo_url'         => 'nullable|string',
                'region_code'       => 'nullable|string',
                'default_language'  => 'nullable|string',
                'region_code_flag'  => 'nullable|string',
                'email_update'      => 'nullable|email',
                'alternative_mobile'=> 'nullable|string',
                'address'           => 'nullable|string',
                'birthdate'         => 'nullable|date',
                'nationality'       => 'nullable|string',
                'job_title'         => 'nullable|string',
                'job_start_date'    => 'nullable|date',
                'job_type'          => 'nullable|string',
                'time_zone'         => 'nullable|string',
                'referral'          => 'nullable|string',
                'last_seen'         => 'nullable|date',
                'is_online'         => 'nullable|boolean',
                'phone_verified_at' => 'nullable|date',
            ]);
        
            DB::beginTransaction();

            //Table : Engineers
            $updated = Engineer::where('id', $validatedEnggData['id'])->update($validatedEnggData);
    
            if (!$updated) {
                throw new Exception('Failed to update Engineer details.');
            }
    
            DB::commit();
    
            return response()->json('Engineer Details Updated Successfully', 200);
    
        } catch (Exception $e) {
            
            DB::rollBack();
    
            return response()->json([
                'message' => 'Error Occurred',
                'error' => $e->getMessage()
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
    }
}
