<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\AuthResource;
use App\Models\Engineer;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\EngineerProfile;
use App\Models\EngineerCharge;
use App\Models\LeaveBalance;
use App\Rules\EngineerUniqueMobileRule;

class AuthController extends Controller
{

    public function login(Request $request)
    {

        // $credentials = $request->only('email', 'password');

        // Find the engineer by email
        $user = Engineer::where('email', $request->email)->first();

        // Check if user exists and if the password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Create a token for the user
        $token = $user->createToken('authToken')->accessToken;

        // Save device token if provided
        if ($request->filled('device_token')) {
            $user->device_token = $request->device_token;
            $user->save();
        }

        // Return response with user data and token
        return response()->json([
            'user' => new EngineerProfile($user),
            'token' => $token,
            'role' => 'engineer'
        ], 200);

        // if (Auth::guard('api_engineer')->attempt($credentials)) {
        //     // $user = Auth::guard('api_engineer')->getLastAttempted();
        //     $user = Auth::guard('api_engineer')->user();
        //     if ($user) {
        //         $token = $user->createToken('authToken')->plainTextToken;
        //         return response()->json([
        //             'user'  => new AuthResource($user),
        //             'token' => $token,
        //             'role'  => $user->roles[0]->name,
        //         ], 200);
        //     }
        // } else {
        //     return response()->json([
        //         'message' => 'Invalid credentials'
        //     ], 401);
        // }
    }

    public function register(Request $request)
    {


        try {

            $validatedData = $request->validate([
                'name'          => 'nullable|string',
                'first_name'    => 'required|string',
                'last_name'     => 'required|string',
                'email'         => 'required|email|unique:engineers,email',
                'password'      => 'required|min:8',
                'country_code'  => 'required|string',
                'contact_iso' => [
                    'required',
                    'alpha',
                    'max:4'
                ],
                'contact' => [
                    'required',
                    new EngineerUniqueMobileRule($request->country_code, $request->contact_iso),
                ],
                // 'contact'       => 'required|string|unique:engineers,contact',
                'alternate_country_code' => 'nullable|string',
                'alternate_contact_iso' => [
                    'nullable',
                    'alpha',
                    'max:4'
                ],
                'profile_image' => 'nullable|string',
                'is_system'     => 'nullable|boolean',
                'device_token'  => 'nullable|string',
                'timezone' => 'required',
            ]);

            $validatedData['first_name'] = $validatedData['first_name'];
            $validatedData['last_name'] = $validatedData['last_name'];
            $validatedData['user_name'] = explode('@', $validatedData['email'])[0];
            $validatedData['status'] = 1;
            $validatedData['email_verification'] = 0;
            $validatedData['admin_verification'] = 1;
            $validatedData['phone_verification'] = 0;
            $validatedData['timezone'] = $validatedData['timezone'];;
            $validatedData['country_code'] = ltrim($validatedData['country_code'], '+');
            $validatedData['contact'] = pureMobileNo($validatedData['contact'], $validatedData['contact_iso']);
            $validatedData['contact_iso'] = $validatedData['contact_iso']; 
            $validatedData['device_token'] = $validatedData['device_token']; 
            

            if(!empty($validatedData['alternate_contact_iso']) && $validatedData['alternative_contact'])
            {
                $validatedData['alternate_country_code'] = ltrim($validatedData['alternate_country_code'], '+');
                $validatedData['alternative_contact'] = pureMobileNo($validatedData['alternative_contact'], $validatedData['alternate_contact_iso']);
                $validatedData['alternate_contact_iso'] = $validatedData['alternate_contact_iso']; 
            }
            $otp = random_int(100000, 999999);
            $validatedData['otp'] = $otp;

            // Generate unique engineer_code
            $lastEngineer = Engineer::latest('id')->first(); // Get the last created engineer
            $lastCode = $lastEngineer ? (int) str_replace('AIM-E-', '', $lastEngineer->engineer_code) : 99; // Default to 99 if none exist
            $nextCode = $lastCode + 1; // Increment the code
            $validatedData['engineer_code'] = 'AIM-E-' . $nextCode; // Format the code

            $recipient = $validatedData['email'];
            $subject = "Verify Your Account with Aimbot";

            $content = '
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <meta charset="UTF-8">
                            <title>Email Verification - Aimbot</title>
                        </head>
                        <body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                            <div style="max-width: 600px; margin: auto; background-color: #fff; border-radius: 8px; padding: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
                                <h2 style="color: #1EAAE7;">Hello ' .$validatedData['first_name'] . ' ' . $validatedData['last_name'] .  ',</h2>
                                <p style="font-size: 16px; color: #333;">Thank you for registering with <strong>Aimbot</strong>!</p>
                                <p style="font-size: 16px; color: #333;">To complete your registration and activate your account, please verify your email using the OTP below:</p>
                                <div style="text-align: center; margin: 30px 0;">
                                    <span style="font-size: 24px; color: #000; background-color: #f0f0f0; padding: 12px 24px; border-radius: 8px; display: inline-block; letter-spacing: 4px;">' . $otp . '</span>
                                </div>
                                <p style="font-size: 14px; color: #555;">This OTP is valid for <strong>10 minutes</strong>. Do not share it with anyone to ensure your account’s security.</p>
                                <p style="font-size: 14px; color: #555;">If you didn’t request this, please ignore this email.</p>
                                <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">
                                <p style="font-size: 14px; color: #888;">Best regards,<br>The Aimbot Team<br><a href="https://www.aimbizit.com" style="color: #1EAAE7;">www.aimbizit.com</a></p>
                            </div>
                        </body>
                        </html>
                        ';


            if (isset($request['test_otp_email']) && !empty($request['test_otp_email'])) {
                $recipient = $request['test_otp_email'];
            }

            // Encrypt password
            $validatedData['password'] = bcrypt($validatedData['password']);

            DB::beginTransaction();

            $user = Engineer::create($validatedData);

            EngineerCharge::create([
                'check_in_time' => '08:00:00',
                'check_out_time' => '17:00:00',
                'engineer_id' => $user->id
            ]);

            // LeaveBalance::create([

            // ]);

            Mail::raw("Test Content", function ($message) use ($recipient, $subject, $content) {
                $message->to($recipient)
                    ->subject($subject)->html($content);
            });

            // $token  = $user->createToken('authToken', ['api_engineer'])->plainTextToken;
            // $token  = $user->createToken('authToken', ['api_engineer'])->plainTextToken;
            $token = $user->createToken('authToken')->accessToken;

            DB::commit();

            return response()->json([
                'user'  => new EngineerProfile($user),
                'token' => $token,
                'otp' => $otp
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

    public function resendOTP(Request $request)
    {
        $email = $request['email'];
        $otp = random_int(100000, 999999);
        $subject = "User Verification";
        // $content = "OTP " . $otp;
        $recipient = $email;
        if (isset($request['test_otp_email']) && !empty($request['test_otp_email'])) {
            $recipient = $request['test_otp_email'];
        }
        $user = Engineer::where([
            'email' => $email
        ])->first();
        $content = '
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <meta charset="UTF-8">
                            <title>Email Verification - Aimbot</title>
                        </head>
                        <body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                            <div style="max-width: 600px; margin: auto; background-color: #fff; border-radius: 8px; padding: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
                                <h2 style="color: #1EAAE7;">Hello ' .$user['first_name'] . ' ' . $user['last_name'] .  ',</h2>
                                <p style="font-size: 16px; color: #333;">Thank you for registering with <strong>Aimbot</strong>!</p>
                                <p style="font-size: 16px; color: #333;">To complete your registration and activate your account, please verify your email using the OTP below:</p>
                                <div style="text-align: center; margin: 30px 0;">
                                    <span style="font-size: 24px; color: #000; background-color: #f0f0f0; padding: 12px 24px; border-radius: 8px; display: inline-block; letter-spacing: 4px;">' . $otp . '</span>
                                </div>
                                <p style="font-size: 14px; color: #555;">This OTP is valid for <strong>10 minutes</strong>. Do not share it with anyone to ensure your account’s security.</p>
                                <p style="font-size: 14px; color: #555;">If you didn’t request this, please ignore this email.</p>
                                <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">
                                <p style="font-size: 14px; color: #888;">Best regards,<br>The Aimbot Team<br><a href="https://www.aimbizit.com" style="color: #1EAAE7;">www.aimbizit.com</a></p>
                            </div>
                        </body>
                        </html>
                        ';
        if ($user) {
            $user->update([
                'otp' => $otp
            ]);
            Mail::raw("Test User Verification", function ($message) use ($recipient, $subject, $content) {
                $message->to($recipient)
                    ->subject($subject)->html($content);
            });
            return response()->json([
                'message'  => 'OTP Send succesfull.',
                'status' => true,
                'otp' => $otp
            ], 200);
        } else {
            return response()->json([
                'message'  => 'User not found.',
                'status' => false,
                'otp' => ''
            ], 404);
        }
    }

    public function verifyOTP(Request $request)
    {
        $email = $request->email;
        $otp = $request->otp;
        $user = Engineer::where([
            'email' => $email
        ])->first();
        if ($user->otp == $otp) {
            $user->update([
                'otp' => null,
                'email_verification' => 1,
            ]);
            return response()->json([
                'message'  => 'OTP verification succesfull.',
                'status' => true
            ], 200);
        } else {
            return response()->json([
                'message'  => 'OTP verification failed.',
                'status' => false
            ], 200);
        }
    }

    public function updateProfile(Request $request, $id)
    {
        try{
            // Validate the incoming request
            $validatedData = $request->validate([
                'country_code'  => 'required|string',
                'contact_iso' => [
                    'required',
                    'alpha',
                    'max:4'
                ],
                'contact' => [
                    'required',
                    new EngineerUniqueMobileRule($request->country_code, $request->contact_iso, $id),
                ],
                'alternate_country_code' => 'nullable|string',
                'alternate_contact_iso' => [
                    'nullable',
                    'alpha',
                    'max:4'
                ],
                'timezone' => 'required',
                // 'first_name' => 'required|string|max:255',
                // 'last_name' => 'required|string|max:255',
                // 'email' => 'required|email|unique:engineers,email,' . $id,
                // 'username' => 'required|string|unique:engineers,username,' . $id,
                // 'contact' => 'required|string|max:15',
                // 'about' => 'nullable|string',
                // 'gender' => 'nullable|in:male,female,other',
                // 'alternative_contact' => 'nullable|string|max:15',
                // 'birthdate' => 'nullable|date',
                // 'nationality' => 'nullable|string|max:50',
            ]);

            $engineer = Engineer::find($request->id);

            $validatedData = $request;

            // Find the Engineer by ID
            $engineer = Engineer::find($request->id);
            $profile_image = '';

            $data = [
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'contact' => $validatedData['contact'],
                'contact_iso' => $validatedData['contact_iso'],
                'about' => $validatedData['about'] ?? $engineer->about,
                'gender' => $validatedData['gender'] ?? $engineer->gender,

                'alternative_contact' => $validatedData['alternative_contact'] ?? $engineer->alternative_contact,
                'birthdate' => $validatedData['birthdate'] ?? $engineer->birthdate,
                'nationality' => $validatedData['nationality'] ?? $engineer->nationality,
                'referral' => $validatedData['referral'] ?? $engineer->referral,
                'addr_apartment' => $validatedData['address']['apartment'] ?? '',
                'addr_street' => $validatedData['address']['street'] ?? '',
                'addr_address_line_1' => $validatedData['address']['address_line_1'] ?? '',
                'addr_address_line_2' => $validatedData['address']['address_line_2'] ?? '',
                'addr_zipcode' => $validatedData['address']['zipcode'] ?? '',
                'addr_city' => $validatedData['address']['city'] ?? '',
                'addr_country' => $validatedData['address']['country'] ?? '',
                'country_code' => $validatedData['country_code'] ?? '',
                'alternate_country_code' => $validatedData['alternate_country_code'] ?? '',
                'alternate_contact_iso' => $validatedData['alternate_contact_iso'] ?? '',
                // 'profile_image' => $profile_image,
            ];
            if(isset( $validatedData['timezone'] ))
            {
                $data['timezone'] = $validatedData['timezone'];
            }

            if ($request->hasFile('profile_image') && $request->file('profile_image')->isValid()) {
                $fileExtension = $request->file('profile_image')->getClientOriginalExtension();
                $fileName = 'profile_' . time() . '.' . $fileExtension;
                $profile_image = $request->file('profile_image')->storeAs('profiles', $fileName, 'public');
                // $profile_image = '/storage/profiles/' . $fileName;
                $data['profile_image'] = $profile_image;
            }

            // Update the engineer's profile
            $engineer->update($data);

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Engineer profile updated successfully.',
                'data' => $engineer,
            ]);
        } catch (Exception $e) {

            // DB::rollBack();

            // Return error response
            return response()->json([
                'message' => 'Error Occurred',
                'error' => $e->getMessage()
            ], 500);
        }

        
    }

    public function updateProfileImageVerification(Request $request, $id)
    {

        $engineer = Engineer::find($request->id);

        $data['profile_image'] = $engineer->profile_image;

        if ($request->hasFile('profile_image') && $request->file('profile_image')->isValid()) {
            $fileExtension = $request->file('profile_image')->getClientOriginalExtension();
            $fileName = 'profile_' . time() . '.' . $fileExtension;
            $profile_image = $request->file('profile_image')->storeAs('profiles', $fileName, 'public');
            // $profile_image = '/storage/profiles/' . $fileName;
            $data['profile_image'] = $profile_image;
        }

        if ($request->phone_verification) {
            $data['phone_verification'] = $request->phone_verification;
        }

        if ($request->gdpr_consent) {
            $data['gdpr_consent'] = $request->gdpr_consent == 1 ? 1 : 0;
        }

        $engineer->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Engineer profile updated successfully.',
            'data' => $engineer,
        ]);
    }

    public function getProfile($id)
    {
        $engineer = Engineer::with('enggCharge', 'enggExtraPay')->find($id);
        return response()->json([
            'success' => true,
            'message' => 'Engineer profile updated successfully.',
            'data' => new EngineerProfile($engineer),
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $email = $request->email;

        // Check if user exists
        $user = Engineer::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
                'status' => false,
            ], 404);
        }

        // Generate OTP
        $otp = random_int(100000, 999999);

        // Update OTP in database
        $user->update([
            'otp' => $otp
        ]);

        // Send OTP to user's email
        // $subject = "Password Reset OTP";
        // $content = "Your password reset OTP is: " . $otp;
        // Mail::raw($content, function ($message) use ($email, $subject) {
        //     $message->to($email)->subject($subject);
        // });

        return response()->json([
            'message' => 'OTP sent successfully.',
            'otp' => $otp,
            'status' => true,
        ], 200);
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:8|confirmed',
            ]);

            // Find user by email
            $user = Engineer::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'message' => 'User not found.',
                    'status' => false,
                ], 404);
            }

            // Update the password
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'message' => 'Password reset successfully.',
                'status' => true,
            ], 200);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Password reset error: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while resetting the password.',
                'status' => false,
            ], 500);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                // 'email' => 'required|email',
                'current_password' => 'required',
                'password' => 'required|min:8',
                'confirm_password' => 'required|same:password', // Custom field validation
            ]);

            // Find user by email
            $user = Engineer::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'message' => 'User not found.',
                    'status' => false,
                ], 404);
            }

            // Check if the current password matches the one in the database
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'message' => 'Current password is incorrect.',
                    'status' => false,
                ], 400); // 400 Bad Request
            }

            // Update the user's password
            $user->update([
                'password' => Hash::make($request->password), // Hash the new password
            ]);

            return response()->json([
                'message' => 'Password changed successfully.',
                'status' => true,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'message' => 'An error occurred while changing the password.',
                'error' => $e->getMessage(),
                'status' => false,
            ], 500);
        }
    }

    public function logout()
    {
        $engineer = Auth::guard('api_engineer')->user();

        Auth::guard('api_engineer')->user()->token()->revoke();

        $engineer->device_token = null;
        $engineer->save();

        return response()->json([
            'status' => true,
            'message' => 'Successfully, Log Out.'
        ], 200);
    }
}
