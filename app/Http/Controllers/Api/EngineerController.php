<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateDeviceTokenRequest;
use App\Http\Resources\EngineerCalendarLeaveRS;
use App\Models\EngineerDocument;
use App\Models\EngineerEducation;
use App\Models\EngineerTravelDetail;
use App\Models\EngineerPaymentDetail;
use App\Http\Resources\PaymentDetailResource;
use App\Http\Resources\EngineerLanguageResource;
use App\Http\Resources\EngineerSkillsResource;
use App\Http\Resources\EngineerIndustryExperianceResource;
use App\Http\Resources\EngineerEducationResource;
use App\Http\Resources\EngineerDocumentsResource;
use App\Http\Resources\MasterDataResource;
use App\Http\Resources\EngineerRightToWork;
use App\Http\Resources\EngineerTechnicalCertification;
use App\Http\Resources\LeaveApplicationResource;
use App\Models\EngineerLanguageSkill;
use App\Models\EngineerSkill;
use App\Models\EngineerIndustryExperience;
use App\Models\MasterData;
use App\Models\RightToWork;
use App\Models\TechnicalCertification;
use App\Models\EngineerLeave;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Ticket;
use App\Models\MonthlyLeaveBalance;
use App\Http\Resources\TicketResource;
use App\Models\Engineer;
use App\Models\Holiday;
use App\Models\TicketWork;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EngineerController extends Controller
{

    public  function dashboardData($engineer_id)
    {
        // Get the start and end of the current week
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();
        // Get the current date
        $currentDate = Carbon::now();

        // Query to fetch tickets where the current date is between task_start_date and task_end_date
        $tickets = Ticket::with(['ticketWork.breaks', 'ticketWork.workExpense', 'engCharge', 'ticketWork.workNote', 'engineer', 'customer', 'lead'])
            ->whereDate('task_start_date', '<=', $currentDate)
            ->whereDate('task_end_date', '>=', $currentDate)
            ->where('engineer_id', $engineer_id)
            ->whereNot('engineer_status', 'offered')
            ->orderBy('task_start_date', 'asc')
            ->orderBy('task_time', 'asc')
            ->get();

        // Query to get tickets grouped by day of the week

        $ticketsByDay = Ticket::whereBetween('task_start_date', [$startOfMonth, $endOfMonth])
            ->where('engineer_id', $engineer_id)
            ->selectRaw('DAYNAME(task_start_date) as day, COUNT(*) as count')
            ->groupBy('day')
            ->pluck('count', 'day');

        $engineer = Engineer::with('enggCharge')->where('id', $engineer_id)->first();
        // Fetch the sum of the required fields for the current month
        $query_totalPayable = TicketWork::whereBetween('work_start_date', [$startOfMonth, $endOfMonth])
            ->where('user_id', $engineer_id);
        if(
            isset($engineer) && $engineer->job_type === 'full_time'
        )
        {
            $totalPayable = $query_totalPayable->sum(DB::raw('COALESCE(other_pay, 0) + COALESCE(overtime_payable, 0) + COALESCE(out_of_office_payable, 0) + COALESCE(weekend_payable, 0) + COALESCE(holiday_payable, 0)'));

            if(isset($engineer->enggCharge))
            {
                $totalPayable = $totalPayable + $engineer->enggCharge->monthly_charge;
            }
        }else{
            $totalPayable = $query_totalPayable->sum(DB::raw('COALESCE(hourly_payable, 0) + COALESCE(other_pay, 0) + COALESCE(overtime_payable, 0) + + COALESCE(out_of_office_payable, 0) + COALESCE(weekend_payable, 0) + COALESCE(holiday_payable, 0)'));
        }

        // Map the results to all days of the week
        // $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        // $weeklyData = collect($daysOfWeek)->map(function ($day) use ($ticketsByDay) {
        //     return [
        //         'x' => strtolower(substr($day, 0, 3)), // Convert to "mon", "tue", etc.
        //         'y' => $ticketsByDay->get($day, 0), // Default to 0 if no tickets
        //     ];
        // });

        // Retrieve tickets for the current month, grouped by day of the month
        $ticketsByDay = Ticket::whereBetween('task_start_date', [$startOfMonth, $endOfMonth])
            ->where('engineer_id', $engineer_id)
            ->get();

        // Prepare data for each day of the current month
        // $daysInMonth = $currentDate->daysInMonth;
        // $monthlyData = collect(range(1, $daysInMonth))->map(function ($day) use ($ticketsByDay) {
        //     return [
        //         'x' => str_pad($day, 2, '0', STR_PAD_LEFT), // Format day as two digits
        //         'y' => $ticketsByDay->get($day, 0), // Default to 0 if no tickets
        //     ];
        // });

        $ticketsPendingApprovals = Ticket::whereBetween('task_start_date', [$startOfMonth, $endOfMonth])->where([
            'engineer_status' => 'offered',
            'engineer_id' => $engineer_id
        ])->count();

        $ticketResolvedCount = Ticket::where([
            'status' => 'close',
            'engineer_id' => $engineer_id
        ])->whereBetween('task_start_date', [$startOfMonth, $endOfMonth])->count();

        $dashboard = [
            'dashboard' => [
                'overview' => [
                    'monthly_tickets' => $ticketsByDay->count(),
                    'pending_approvals' => $ticketsPendingApprovals,
                    'resolved_tickets' => $ticketResolvedCount,
                    'payouts' => $totalPayable,
                ],
                'tickets' => TicketResource::collection($tickets),
            ]
        ];

        return response()->json([
            'success' => true,
            'message' => 'Dashboard Data fatched successfully.',
            'data' => $dashboard
        ]);
    }

    ///// Engineer Document

    public function saveDocument(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'id' => 'nullable',
            'user_id' => 'required',
            'document_type' => 'required|string|max:255',
            'status' => 'required',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'remove_files' => 'nullable',
        ]);

        $data = [
            'user_id' => $validatedData['user_id'],
            'document_type' => $validatedData['document_type'],
            'status' => $validatedData['status'],
            'issue_date' => $validatedData['issue_date'],
            'expiry_date' => $validatedData['expiry_date'],
            // 'media_file' => json_encode($mediaFiles),
        ];

        $mediaFiles = [];

        if (!empty($validatedData['id'])) {
            $engineerDocument = EngineerDocument::findOrFail($validatedData['id']);
            $mediaFiles =  $engineerDocument->media_file ? json_decode($engineerDocument->media_file) : [];
        }

        // Check if 'remove_files' exists and is an array
        if (isset($validatedData['remove_files']) && is_array($validatedData['remove_files'])) {

            // Remove specified files from the mediaFiles array
            $mediaFiles = array_diff($mediaFiles, $validatedData['remove_files']);

            // Reindex the array to ensure keys are sequential
            $mediaFiles = array_values($mediaFiles);

            // Delete the files from storage
            foreach ($validatedData['remove_files'] as $file) {
                $filePath = storage_path('app/public/' . $file);
                if (file_exists($filePath)) {
                    unlink($filePath); // Delete the file from storage
                }
            }
        }

        // Handle media files if they exist
        if ($request->hasFile('media_files')) {
            foreach ($request->file('media_files') as $file) {
                $path = $file->store('engineer_documents', 'public');
                $absolutePath = storage_path('app/public/' . $path);
                chmod($absolutePath, 0644);
                $mediaFiles[] = $path;
            }
        }

        $data['media_file'] = json_encode($mediaFiles);

        // $engineerDocument = EngineerDocument::create();
        $engineerDocument = EngineerDocument::updateOrCreate(
            ['id' => $validatedData['id']],
            $data
        );

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Engineer document saved successfully.',
            'data' => new EngineerDocumentsResource($engineerDocument),
        ], 201);
    }

    public function listDocuments(Request $request, $id = null)
    {
        $documents = EngineerDocument::where([
            'user_id' => $id
        ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Return paginated list of documents
        return response()->json([
            'success' => true,
            'message' => 'Engineer documents retrieved successfully.',
            'data' => EngineerDocumentsResource::collection($documents),
        ]);
    }

    public function getDocumentDetails(Request $request, $id = null)
    {
        $document = EngineerDocument::where([
            'id' => $id
        ])->first();
        return response()->json([
            'success' => true,
            'message' => 'Engineer document detail retrieved successfully.',
            'data' => new EngineerDocumentsResource($document),
        ]);
    }

    public function deleteDocument(Request $request, $id = null)
    {
        EngineerDocument::where([
            'id' => $id
        ])->delete();
        return response()->json([
            'success' => true,
            'message' => 'Engineer document deleted successfully.',
        ]);
    }

    ////// Engineer Education  

    public function saveEducation(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'id' => 'nullable', // For updates
            'user_id' => 'required',
            'degree_name' => 'required|string|max:255',
            'university_name' => 'required|string|max:255',
            'issue_date' => 'nullable|date',
            'status' => 'required|in:0,1', // Example: 0 = inactive, 1 = active
            'media_files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf', // Validate uploaded files
            'remove_files' => 'nullable'
        ]);

        $data = [
            'user_id' => $validatedData['user_id'],
            'degree_name' => $validatedData['degree_name'],
            'university_name' => $validatedData['university_name'],
            'issue_date' => $validatedData['issue_date'],
            'status' => $validatedData['status'],
            // 'media_files' => json_encode($mediaFiles), // Save media files
        ];

        // Initialize or fetch existing media files
        $mediaFiles = [];
        if (!empty($validatedData['id'])) {
            $education = EngineerEducation::findOrFail($validatedData['id']);
            $mediaFiles =  $education->media_files ? json_decode($education->media_files) : [];
        } else {
            // $data['user_id'] = $validatedData['user_id'] ;
        }

        // Handle file removal if remove_files is provided
        if (isset($validatedData['remove_files']) && is_array($validatedData['remove_files'])) {
            $mediaFiles = array_diff($mediaFiles, $validatedData['remove_files']);
            $mediaFiles = array_values($mediaFiles);

            foreach ($validatedData['remove_files'] as $file) {
                $filePath = storage_path('app/public/' . $file);
                if (file_exists($filePath)) {
                    unlink($filePath); // Delete the file from storage
                }
            }
        }

        // Handle media file uploads
        if ($request->hasFile('media_files')) {
            foreach ($request->file('media_files') as $file) {
                $path = $file->store('engineer_educations', 'public');
                $mediaFiles[] = $path;
            }
        }

        $data['media_files'] = json_encode($mediaFiles);

        // Save or update education record
        $education = EngineerEducation::updateOrCreate(
            ['id' => $validatedData['id']],
            $data
        );

        // Return success response
        return response()->json([
            'success' => true,
            'message' => $validatedData['id']
                ? 'Engineer education updated successfully.'
                : 'Engineer education saved successfully.',
            'data' => new EngineerEducationResource($education),
        ], 201);
    }

    public function listEducations(Request $request, $id = null)
    {
        $educations = EngineerEducation::where([
            'user_id' => $id
        ])
            ->orderBy('created_at', 'desc')
            ->get();
        // Return paginated list of documents
        return response()->json([
            'success' => true,
            'message' => 'Engineer educations retrieved successfully.',
            'data' => EngineerEducationResource::collection($educations),
        ]);
    }

    public function getEducationDetails(Request $request, $id = null)
    {
        $education = EngineerEducation::where([
            'id' => $id
        ])->first();
        return response()->json([
            'success' => true,
            'message' => 'Engineer education detail retrieved successfully.',
            'data' => new EngineerEducationResource($education),
        ]);
    }

    public function deleteEducation(Request $request, $id = null)
    {
        EngineerEducation::where([
            'id' => $id
        ])->delete();
        return response()->json([
            'success' => true,
            'message' => 'Engineer education deleted successfully.',
        ]);
    }

    ////  Enginner Vehicale

    public function saveTravelDetail(Request $request)
    {
        Log::info([$request->all()]);

        $travelDetail = EngineerTravelDetail::where([
            'user_id' => $request['user_id']
        ])->first();

        $travelRequest = [
            'user_id' => $request['user_id'],
            'driving_license' => (int) $request->input('driving_license'), // Convert string "1" to integer 1
            'own_vehicle' => (int) $request->input('own_vehicle'), // Convert "1" to 1
            'working_radius' => $request['working_radius'],
            'type_of_vehicle' => json_encode($request['type_of_vehicle']),
        ];

        Log::info(['driving_license_after_cast' => $travelRequest['driving_license']]);

        if ($travelDetail) {
            $travelDetail->update($travelRequest);
        } else {
            EngineerTravelDetail::create($travelRequest);
        }

        return response()->json([
            'success' => true,
            'message' => 'Travel detail updated successfully.',
        ]);
    }

    public function getTravelDetail($engineer_id)
    {

        $travelDetail = EngineerTravelDetail::where([
            'user_id' => $engineer_id
        ])->first();
        if ($travelDetail) {
            return response()->json([
                'data' => [
                    'user_id' => $engineer_id,
                    'driving_license' => $travelDetail->driving_license,
                    'own_vehicle' => $travelDetail->own_vehicle,
                    'working_radius' => $travelDetail->working_radius,
                    'type_of_vehicle' =>  json_decode($travelDetail->type_of_vehicle),
                ],
                'success' => true,
                'message' => 'Travel detail get successfully.',
            ]);
        } else {
            return response()->json([
                'data' => [
                    'user_id' => $engineer_id,
                    'driving_license' => null,
                    'own_vehicle' => null,
                    'working_radius' => null,
                    'type_of_vehicle' => [],
                ],
                'success' => true,
                'message' => 'Travel detail get successfully.',
            ]);
        }
    }

    ////  Payment Details
    public function savePaymentDetail(Request $request)
    {

        $paymentDetail = EngineerPaymentDetail::where([
            'user_id' => $request['user_id']
        ])->first();

        $paymentRequest = [
            'user_id' => $request['user_id'],
            'payment_currency' => $request['payment_currency'],
            'bank_name' => $request['bank_name'],
            'account_type'  => $request['account_type'],
            'holder_name'  => $request['holder_name'],
            'account_number'  => $request['account_number'],
            'bank_address'  => $request['bank_address'],
            'iban'  => $request['iban'],
            'swift_code'  => $request['swift_code'],
            'routing'  => $request['routing'],
            'personal_tax_id'  => $request['personal_tax_id'],
            'sort_code'  => $request['sort_code'],
        ];

        if ($paymentDetail) {
            $paymentDetail->update($paymentRequest);
        } else {
            EngineerPaymentDetail::create($paymentRequest);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment detail updated successfully.',
        ]);
    }

    public function getPaymentDetail($engineer_id)
    {
        $paymentDetail = EngineerPaymentDetail::where([
            'user_id' => $engineer_id
        ])->first();
        if ($paymentDetail) {
            return response()->json([
                'data' => new PaymentDetailResource($paymentDetail),
                'success' => true,
                'message' => 'Travel detail get successfully.',
            ]);
        } else {
            return response()->json([
                'data' => new PaymentDetailResource($paymentDetail),
                'success' => true,
                'message' => 'Payment detail get successfully.',
            ]);
        }
    }

    ////  Language Skills

    public function saveLanguageSkill(Request $request)
    {
        $requestData = [
            'user_id' => $request['user_id'],
            'language_name' => $request['language_name'],
            'proficiency_level' => $request['proficiency_level'],
            'read' => $request['read'],
            'write' => $request['write'],
            'speak' => $request['speak']
        ];
        if ($request->id) {
            EngineerLanguageSkill::where([
                'id' => $request->id
            ])->update($requestData);
        } else {
            EngineerLanguageSkill::create($requestData);
        }
        return response()->json([
            'success' => true,
            'message' => 'Language detail save successfully.',
        ]);
    }

    public function listLanguageSkills($engineer_id)
    {
        $engineerLanguageList = EngineerLanguageSkill::where([
            'user_id' => $engineer_id
        ])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => EngineerLanguageResource::collection($engineerLanguageList),
            'success' => true,
            'message' => 'Language list get successfully.',
        ]);
    }

    public function getLanguageSkillDetail($language_id)
    {
        $engineerLanguageDetail = EngineerLanguageSkill::where([
            'id' => $language_id
        ])->first();
        return response()->json([
            'data' => new EngineerLanguageResource($engineerLanguageDetail),
            'success' => true,
            'message' => 'Language detail get successfully.',
        ]);
    }

    public function deleteLanguageSkill(Request $request)
    {
        EngineerLanguageSkill::where([
            'id' => $request['id']
        ])->delete();
        return response()->json([
            'success' => true,
            'message' => 'Language detail deleted successfully.',
        ]);
    }

    //// Engineer Skills
    public function saveSkill(Request $request)
    {

        $requestData = [
            'user_id' => $request['user_id'],
            'name' => $request['name'],
            'level' => $request['level'],
        ];

        if ($request->id) {
            EngineerSkill::where('id', $request->id)->update($requestData);
        } else {
            EngineerSkill::create($requestData);
        }

        return response()->json([
            'success' => true,
            'message' => 'Skill saved successfully.',
        ]);
    }

    public function listSkills($engineer_id)
    {
        $skillsList = EngineerSkill::where('user_id', $engineer_id)->orderBy('created_at', 'desc')->get();
        return response()->json([
            'data' => EngineerSkillsResource::collection($skillsList),
            'success' => true,
            'message' => 'Skills list retrieved successfully.',
        ]);
    }

    public function getSkillDetail($skill_id)
    {
        $skillDetail = EngineerSkill::where('id', $skill_id)->first();

        return response()->json([
            'data' => new EngineerSkillsResource($skillDetail),
            'success' => true,
            'message' => 'Skill detail retrieved successfully.',
        ]);
    }

    public function deleteSkill(Request $request)
    {
        EngineerSkill::where('id', $request['id'])->delete();

        return response()->json([
            'success' => true,
            'message' => 'Skill deleted successfully.',
        ]);
    }

    //// Engineer Industry Experience
    public function saveIndustryExperience(Request $request)
    {
        $requestData = [
            'user_id' => $request['user_id'],
            'name' => $request['name'],
            'experience' => $request['experience'], // ['expert', 'moderate', 'beginner']
        ];

        if ($request->id) {
            EngineerIndustryExperience::where('id', $request->id)->update($requestData);
        } else {
            EngineerIndustryExperience::create($requestData);
        }

        return response()->json([
            'success' => true,
            'message' => 'Industry experience saved successfully.',
        ]);
    }

    public function listIndustryExperience($engineer_id)
    {
        $industryExperienceList = EngineerIndustryExperience::where('user_id', $engineer_id)->orderBy('created_at', 'desc')->get();
        return response()->json([
            'data' => EngineerIndustryExperianceResource::collection($industryExperienceList),
            'success' => true,
            'message' => 'Industry experience list retrieved successfully.',
        ]);
    }

    public function getIndustryExperienceDetail($experience_id)
    {
        $industryExperienceDetail = EngineerIndustryExperience::where('id', $experience_id)->first();

        return response()->json([
            'data' => new EngineerIndustryExperianceResource($industryExperienceDetail),
            'success' => true,
            'message' => 'Industry experience detail retrieved successfully.',
        ]);
    }

    public function deleteIndustryExperience(Request $request)
    {
        EngineerIndustryExperience::where('id', $request['id'])->delete();

        return response()->json([
            'success' => true,
            'message' => 'Industry experience deleted successfully.',
        ]);
    }

    //  Master Data API
    public function getMasterData(Request $request, $type)
    {

        $masteData = MasterData::where([
            'type' => $type
        ])->orderBy('key_name', 'asc')->get();

        return response()->json([
            'data' => MasterDataResource::collection($masteData),
            'success' => true,
            'message' => 'Master data get successfully.',
        ]);
    }

    //  Right To Work
    public function saveRightToWork(Request $request)
    {

        // Validate the incoming request
        $validatedData = $request->validate([
            'user_id' => 'required',
            'type' => 'required|string|max:255',
            // 'document_type' => 'nullable',
            'university_certificate_file' => 'nullable|file',
            'visa_copy_file' => 'nullable|file',
            'status' => 'required|in:0,1',
            'other_name' => 'nullable',
            'issue_date' => 'nullable|date',
            'expire_date' => 'nullable|date',
        ]);

        // Check if a record with the same user_id already exists
        $rightToWork = RightToWork::where('user_id', $validatedData['user_id'])->first();

        $documentFile = null;
        $universityCertificateFile = null;
        $visaCopyFile = null;

        // Handle document file if it exists
        if ($request->hasFile('document_file')) {
            $documentFile = $request->file('document_file')->store('right_to_work_documents', ['disk' => 'public', 'visibility' => 'public']); // Store in 'storage/app/public/right_to_work_documents'
        } else {
            if (!empty($rightToWork)) {
                $documentFile = $rightToWork->document_file;
            }
        }

        // Handle university certificate file if it exists
        if ($request->hasFile('university_certificate_file')) {
            $universityCertificateFile = $request->file('university_certificate_file')->store('right_to_work_documents', ['disk' => 'public', 'visibility' => 'public']); // Store in 'storage/app/public/right_to_work_documents'
        } else {
            if (!empty($rightToWork)) {
                $universityCertificateFile = $rightToWork->university_certificate_file;
            }
        }

        if ($request->hasFile('visa_copy_file')) {
            $visaCopyFile = $request->file('visa_copy_file')->store('right_to_work_documents', ['disk' => 'public', 'visibility' => 'public']); // Store in 'storage/app/public/right_to_work_documents'
        } else {
            if (!empty($rightToWork)) {
                $visaCopyFile = $rightToWork->visa_copy_file;
            }
        }


        if ($rightToWork) {
            // Update the existing record
            $rightToWork->update([
                'type' => $validatedData['type'],
                'document_type' => isset($request->document_type) ?? null,
                'document_file' => $documentFile, // Keep existing file if no new file is uploaded
                'issue_date' => $validatedData['issue_date'],
                'university_certificate_file' => $universityCertificateFile,
                'visa_copy_file' => $visaCopyFile,
                'expire_date' => $validatedData['expire_date'],
                'other_name' => $validatedData['other_name'],
                'status' => $validatedData['status'],
            ]);
            $message = 'Right to Work document updated successfully.';
        } else {
            // Create a new record
            $rightToWork = RightToWork::create([
                'user_id' => $validatedData['user_id'],
                'type' => $validatedData['type'],
                'document_type' => isset($request->document_type) ?? null,
                'document_file' => $documentFile,
                'university_certificate_file' => $universityCertificateFile,
                'visa_copy_file' => $visaCopyFile,
                'issue_date' => $validatedData['issue_date'],
                'expire_date' => $validatedData['expire_date'],
                'other_name' => $validatedData['other_name'],
                'status' => $validatedData['status'],
            ]);
            $message = 'Right to Work document saved successfully.';
        }

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => new EngineerRightToWork($rightToWork),
        ], 201);
    }

    public function deleteRightToWork(Request $request)
    {
        $validatedData = $request->validate([
            'engineer_id' => 'required',
        ]);
        RightToWork::where('user_id', $validatedData['engineer_id'])->delete();
        return response()->json([
            'success' => true,
            'message' => 'Right to work reset successfully.',
        ], 200);
    }

    public function getRightToWorkRecord($engineer_id)
    {
        // Fetch the record by engineer_id
        $rightToWork = RightToWork::where('user_id', $engineer_id)->first();

        if ($rightToWork) {
            return response()->json([
                'success' => true,
                'message' => 'Right to Work record retrieved successfully.',
                'data' => new EngineerRightToWork($rightToWork), // Assuming you have a resource for formatting
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Right to Work record not found.',
            ], 404);
        }
    }

    //// Technical Certification
    public function saveTechnicalCertification(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'id' => 'nullable',
            'user_id' => 'required',
            'certification_type' => 'required|string|max:255',
            'certification_id' => 'required',
            'status' => 'required',
            'issue_date' => 'nullable',
            'expire_date' => 'nullable',
        ]);

        $certificateFile = null;


        // Handle certificate file upload
        if ($request->hasFile('certificate_file')) {
            $certificateFile = $request->file('certificate_file')->store('technical_certifications', 'public');
        } else {
            $technicalCertification = TechnicalCertification::findOrFail($validatedData['id']);
            if (!empty($technicalCertification)) {
                $certificateFile = $technicalCertification->certificate_file;
            }
        }

        $data = [
            'user_id' => $validatedData['user_id'],
            'certification_type' => $validatedData['certification_type'],
            'certification_id' => $validatedData['certification_id'],
            'certificate_file' => $certificateFile,
            'issue_date' => $validatedData['issue_date'],
            'expire_date' => $validatedData['expire_date'],
            'status' => $validatedData['status'],
        ];

        // Update or Create record
        $technicalCertification = TechnicalCertification::updateOrCreate(
            ['id' => $request->id],
            $data
        );

        // Response
        return response()->json([
            'success' => true,
            'message' => 'Technical Certification saved successfully.',
            'data' => new EngineerTechnicalCertification($technicalCertification),
        ], 201);
    }

    public function getTechnicalCertificationDetail($id)
    {
        $technicalCertification = TechnicalCertification::find($id);

        if (!$technicalCertification) {
            return response()->json([
                'success' => false,
                'message' => 'Technical Certification not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Technical Certification detail retrieved successfully.',
            'data' => new EngineerTechnicalCertification($technicalCertification),
        ], 200);
    }

    public function deleteTechnicalCertification(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $technicalCertification = TechnicalCertification::find($request->id);
        if ($technicalCertification) {
            $technicalCertification->delete();
            return response()->json([
                'success' => true,
                'message' => 'Technical Certification deleted successfully.',
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Technical Certification not found.',
        ], 404);
    }

    public function listTechnicalCertifications($user_id)
    {
        $technicalCertifications = TechnicalCertification::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();
        if ($technicalCertifications->isEmpty()) {
            return response()->json([
                'data' => [],
                'success' => false,
                'message' => 'No Technical Certifications found for this user.',
            ], 200);
        }
        return response()->json([
            'success' => true,
            'message' => 'Technical Certifications retrieved successfully.',
            'data' => EngineerTechnicalCertification::collection($technicalCertifications),
        ], 200);
    }

    public function leaveApplication(Request $request)
    {
        try {

            $validatedData = Validator::make($request->all(),[
                'engineer_id' => 'required',
                'paid_from_date' => 'nullable',
                'paid_to_date' => 'nullable',
                'unpaid_from_date' => 'nullable',
                'unpaid_to_date' => 'nullable',
                'paid_leave_days' => 'nullable',
                'unpaid_leave_days' => 'nullable',
                'unpaid_reason' => 'nullable|string',
                'signed_paid_document.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',
                'unsigned_paid_document.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',
                'signed_unpaid_document.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',
                'unsigned_unpaid_document.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',

            ]);

            if ($validatedData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validatedData->errors()->first(),
                    'errors' => $validatedData->errors(),
                ], 422);
            }


            $engineerId = $request->engineer_id;
            $paidFromDate = $request->paid_from_date;
            $paidToDate = $request->paid_to_date;
            $unpaidFromDate = $request->unpaid_from_date;
            $unpaidToDate = $request->unpaid_to_date;

            // **Check if leave already applied for the same date**
            // $leaveExists = EngineerLeave::where('engineer_id', $engineerId)
            //     ->where('leave_approve_status', '!=', 'reject')
            //     ->where(function ($query) use ($paidFromDate, $paidToDate, $unpaidFromDate, $unpaidToDate) {
            //         $query->whereBetween('paid_from_date', [$paidFromDate, $paidToDate])
            //             ->orWhereBetween('paid_to_date', [$paidFromDate, $paidToDate])
            //             ->orWhereBetween('unpaid_from_date', [$unpaidFromDate, $unpaidToDate])
            //             ->orWhereBetween('unpaid_to_date', [$unpaidFromDate, $unpaidToDate]);
            //     })
            //     ->exists();

            $leaveExists = EngineerLeave::where('engineer_id', $engineerId)
                ->where('leave_approve_status', '!=', 'reject')
                ->where(function ($query) use ($paidFromDate, $paidToDate, $unpaidFromDate, $unpaidToDate) {
                    
                    if ($paidFromDate && $paidToDate) {
                        $query->where(function ($q) use ($paidFromDate, $paidToDate) {
                            $q->whereBetween('paid_from_date', [$paidFromDate, $paidToDate])
                            ->orWhereBetween('paid_to_date', [$paidFromDate, $paidToDate])
                            ->orWhereBetween('unpaid_from_date', [$paidFromDate, $paidToDate])
                            ->orWhereBetween('unpaid_to_date', [$paidFromDate, $paidToDate]);
                        });
                    }

                    if ($unpaidFromDate && $unpaidToDate) {
                        $query->orWhere(function ($q) use ($unpaidFromDate, $unpaidToDate) {
                            $q->whereBetween('paid_from_date', [$unpaidFromDate, $unpaidToDate])
                            ->orWhereBetween('paid_to_date', [$unpaidFromDate, $unpaidToDate])
                            ->orWhereBetween('unpaid_from_date', [$unpaidFromDate, $unpaidToDate])
                            ->orWhereBetween('unpaid_to_date', [$unpaidFromDate, $unpaidToDate]);
                        });
                    }

                })
                ->exists();

            if ($leaveExists) {
                return response()->json([
                    'data' => [],
                    'success' => false,
                    'message' => 'Leave already applied for this day.',
                ], 422);
            }



            $signedPaidDocument = null;
            $unsignedPaidDocument = null;
            $signedUnpaidDocument = null;
            $unsignedUnpaidDocument = null;



            // sign - unsign documetns

            if ($request->hasFile('signed_paid_document')) {
                $signedPaidDocument = $request->file('signed_paid_document')->store('leave_documents', 'public');
            }


            if ($request->hasFile('unsigned_paid_document')) {
                $unsignedPaidDocument = $request->file('unsigned_paid_document')->store('leave_documents', 'public');
            }


            if ($request->hasFile('signed_unpaid_document')) {
                $signedUnpaidDocument = $request->file('signed_unpaid_document')->store('leave_documents', 'public');
            }

            if ($request->hasFile('unsigned_unpaid_document')) {
                $unsignedUnpaidDocument = $request->file('unsigned_unpaid_document')->store('leave_documents', 'public');
            }

            // end sign - unsign documetns


            $engineerId = $request->engineer_id;

            $requestData = [
                'engineer_id' => $engineerId,
                'paid_from_date' => $request->paid_from_date,
                'paid_to_date' => $request->paid_to_date,
                'unpaid_from_date' => $request->unpaid_from_date,
                'unpaid_to_date' => $request->unpaid_to_date,
                'paid_leave_days' => $request->paid_leave_days,
                'unpaid_leave_days' => $request->unpaid_leave_days,
                'unpaid_reason' => $request->unpaid_reason,
                'note' => $request->note,
                'leave_approve_status' => 'pending',

                'signed_paid_document' => $signedPaidDocument,
                'unsigned_paid_document' => $unsignedPaidDocument,
                'signed_unpaid_document' => $signedUnpaidDocument,
                'unsigned_unpaid_document' => $unsignedUnpaidDocument,

            ];

            Log::info([
                '$requestData' => $requestData
            ]);

            $engineerLeaveBalance = LeaveBalance::where([
                'engineer_id' => $engineerId
            ])->first();

            if ($engineerLeaveBalance) {
                $engineerLeaveBalance->freezed_leave_balance += $request->paid_leave_days;
                $engineerLeaveBalance->save();
            }

            $application = EngineerLeave::create($requestData);

            return response()->json([
                'data' => new LeaveApplicationResource($application),
                'success' => true,
                'message' => 'Leave application save successfully.',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'success' => false,
                'message' => 'Something went wrong!',
            ], 500);
        }
    }

    public function leaveApplicationList(string $id, Request $request)
    {
        try {

            $leaveApplications = EngineerLeave::where([
                'engineer_id' => $id
            ])->orderBy('id', 'DESC')->get();

            $leaveBalance = LeaveBalance::where(['engineer_id' => $id])->first() ?? null;
            $leaveApproved = EngineerLeave::where('engineer_id', $id)->where('leave_approve_status', 'approved')->count();
            $leavePending = EngineerLeave::where('engineer_id', $id)->where('leave_approve_status', 'pending')->count();
            $leaveCancelled = EngineerLeave::where('engineer_id', $id)->where('leave_approve_status', 'reject')->count();


            return response()->json([
                'data' => [
                    'stats' => [
                        'leave_balance' => $leaveBalance?->balance ?  $leaveBalance?->balance : null,
                        'leave_approved' => $leaveApproved,
                        'leave_pending' => $leavePending,
                        'leave_cancelled' => $leaveCancelled,
                        'freezed_leave_balance' => $leaveBalance?->freezed_leave_balance ?  $leaveBalance?->freezed_leave_balance : null
                    ],
                    'list' => LeaveApplicationResource::collection($leaveApplications)
                ],
                'success' => true,
                'message' => 'Leave application list get successfully.',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'success' => false,
                'message' => 'Something went wrong!',
            ], 500);
        }
    }

    public function deleteLeaveApplication(Request $request)
    {
        try {
            EngineerLeave::where([
                'id' => $request->id
            ])->delete();
            return response()->json([
                'success' => true,
                'message' => 'Leave application deleted successfully.',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'success' => false,
                'message' => 'Something went wrong!',
            ], 500);
        }
    }

    public function engineerProfileStatus(string $id)
    {

        $models = [
            [
                'key' => 'education_details',
                'model' => 'EngineerEducation',
            ],
            [
                'key' => 'id_documents',
                'model' => 'EngineerDocument',
            ],
            [
                'key' => 'right_to_work',
                'model' => 'RightToWork',
            ],
            [
                'key' => 'payment_details',
                'model' => 'EngineerPaymentDetail',
            ],
        ];

        $result = [];

        // Check related models dynamically
        foreach ($models as $model) {
            $modelClass = "App\\Models\\{$model['model']}"; // Adjust namespace if needed
            $result[$model['key']] = $modelClass::where('user_id', $id)->exists() ? 1 : 0;
        }

        // Check personal details in Engineer model
        $engineer = Engineer::find($id);

        if ($engineer) {
            $personalDetailsFields = [
                'first_name',
                'last_name',
                'email',
                'contact',
                'gender',
                'birthdate',
                'nationality',
                'addr_apartment',
                'addr_street',
                'addr_address_line_1',
                'addr_address_line_2',
                'addr_zipcode',
                'addr_city',
                'addr_country',
            ];

            // Check if any of the personal details fields are not empty
            $hasPersonalDetails = collect($personalDetailsFields)
                ->some(fn($field) => !empty($engineer->{$field}));

            $result['personal_details'] = $hasPersonalDetails ? 1 : 0;
        } else {
            $result['personal_details'] = 0; // If the engineer record doesn't exist
        }

        return response()->json([
            'data' => $result,
            'success' => true,
            'message' => 'Profile status get succesfully.',
        ], 200);
    }

    public function deleteAccount(Request $request)
    {
        try {
            Engineer::where('id', $request->id)->update([
                'is_deleted' => 1
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Account deleted successfully.',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [],
                'success' => false,
                'message' => 'Something went wrong!',
            ], 500);
        }
    }

    public function allocateMonthlyLeave(Request $request)
    {
        $engineers = Engineer::where([
            'job_type' => 'full_time'
        ])->all();

        // Get current year and month
        $currentYear = date('Y');
        $currentMonth = date('m');

        foreach ($engineers as $engineer) {

            // Update LeaveBalance
            LeaveBalance::where('engineer_id', $engineer->id)
                ->update([
                    'balance' => \DB::raw('balance + 1.66'),
                    'leave_credited_this_month' => 1.66
                ]);

            // Get last month's record for carry forward calculation
            $lastMonth = Carbon::now()->subMonth();
            $lastMonthBalance = MonthlyLeaveBalance::where('engineer_id', $engineer->id)
                ->where('year', $lastMonth->year)
                ->where('month', $lastMonth->month)
                ->first();

            $carryForwardLeaves = $lastMonthBalance ?
                ($lastMonthBalance->allocated_leaves + $lastMonthBalance->carry_forward_leaves - $lastMonthBalance->used_leaves)
                : 0;

            MonthlyLeaveBalance::updateOrCreate(
                [
                    'engineer_id' => $engineer->id,
                    'year' => $currentYear,
                    'month' => $currentMonth
                ],
                [
                    'allocated_leaves' => 1.66,
                    'used_leaves' => 0,
                    'carry_forward_leaves' => $carryForwardLeaves
                ]
            );
        }
    }

    public function allocateYearlyLeave(Request $request)
    {

        $engineers = Engineer::where([
            'job_type' => 'full_time'
        ])->all();

        foreach ($engineers as $engineer) {
            foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12] as $key => $month) {
                $currentYear = date('Y');
                MonthlyLeaveBalance::updateOrCreate(
                    [
                        'engineer_id' => $engineer->id,
                        'year' => $currentYear,
                        'month' => $month
                    ],
                    [
                        'allocated_leaves' => 1.66,
                        'used_leaves' => 0,
                        'carry_forward_leaves' => 0
                    ]
                );
            }
        }
    }

    public function updateDeviceToken(UpdateDeviceTokenRequest $request)
    {
        $request->validated();

        $engineer = Auth::guard('api_engineer')->user();

        $engineer->device_token = $request->device_token ? $request->device_token : null;
        $engineer->save();

        return response()->json([
            'status' => true,
            'message' => 'Updated device token successfully.'
        ], 200);
    }

    public function leave(Request $request)
    {
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $engineer = Auth::guard('api_engineer')->user();

        $engineer_pending_leave = EngineerLeave::whereIn('leave_approve_status', ['pending'])
            ->where(function ($query) use ($start_date, $end_date) {
                $query->whereBetween('paid_from_date', [$start_date, $end_date])
                    ->orWhereBetween('paid_to_date', [$start_date, $end_date])
                    ->orWhereBetween('unpaid_from_date', [$start_date, $end_date])
                    ->orWhereBetween('unpaid_to_date', [$start_date, $end_date]);
            })
            ->where('engineer_id', $engineer->id)
            ->get()
            ->groupBy('engineer_id');

        $engineer_accepted_leave = EngineerLeave::whereIn('leave_approve_status', ['approved'])
            ->where(function ($query) use ($start_date, $end_date) {
                $query->whereBetween('paid_from_date', [$start_date, $end_date])
                    ->orWhereBetween('paid_to_date', [$start_date, $end_date])
                    ->orWhereBetween('unpaid_from_date', [$start_date, $end_date])
                    ->orWhereBetween('unpaid_to_date', [$start_date, $end_date]);
            })
            ->where('engineer_id', $engineer->id)
            ->get()
            ->groupBy('engineer_id');

        $holiday = Holiday::where(function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        })->get()->pluck('date');
        
        return response()->json([
            'status' => true,
            'message' => 'Updated device token successfully.',
            'pending' => EngineerCalendarLeaveRS::collection($engineer_pending_leave),
            'accepted' => EngineerCalendarLeaveRS::collection($engineer_accepted_leave),
            'holiday' => $holiday
        ], 200);
    }
}
