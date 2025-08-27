<?php

namespace App\Http\Controllers;

use App\DataTables\EngineerDataTable;
use App\Http\Resources\EngineerProfile;
use App\Http\Resources\EngineerRightToWork;
use App\Http\Resources\EngineerTechnicalCertification;
use App\Models\Engineer;
use App\Models\EngineerCharge;
use App\Models\EngineerDailyWorkExpense;
use App\Models\EngineerDocument;
use App\Models\EngineerEducation;
use App\Models\User;
use App\Models\EngineerExtraPay;
use App\Models\EngineerIndustryExperience;
use App\Models\EngineerLanguageSkill;
use App\Models\EngineerLeave;
use App\Models\EngineerPayout;
use App\Models\EngineerSkill;
use App\Models\EngineerTechnicalCertification as ModelsEngineerTechnicalCertification;
use App\Models\EngineerTravelDetail;
use App\Models\Lead;
use App\Models\LeaveBalance;
use App\Models\RightToWork;
use App\Models\TicketWork;
use App\Models\WorkBreak;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;

class EngineerController extends Controller
{
    public function dataTable(EngineerDataTable $engineerDataTable)
    {
        return $engineerDataTable->ajax();
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $breadcrumbs = [
            ['name' => 'Home', 'url' => ""],
            ['name' => 'Engineers', 'url' => "/engg"],
        ];

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

        $engineers = Engineer::with('enggCharge')->orderBy('id', 'desc')->get();

        $engineerResults = [];

        foreach ($engineers as $engineer) {

            $total = 0;
            $result = [];

            // Check related models dynamically
            foreach ($models as $model) {
                $modelClass = "App\\Models\\{$model['model']}"; // Adjust namespace if needed
                $result[$model['key']] = $modelClass::where('user_id', $engineer->id)->exists() ? 1 : 0;
                $total += $result[$model['key']];
            }

            // Check personal details in Engineer model
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

            // Optionally add engineer's id or other information
            $result['engineer_id'] = $engineer->id;

            $total += $result['personal_details'];

            // Store the result for the current engineer
            $engineerResults[$engineer->id] = $total;
        }

        return view('backend/engineer/dataTable_list');
        

        // return view('backend/engineer/index', [
        //     'engineers'     => $engineers,
        //     'user'          => null,
        //     'breadcrumbs'   => $breadcrumbs,
        //     'engineer_profile_status' => $engineerResults
        // ]);
    }

    public function ajaxPagination()
    {
        $engineers = Engineer::with('enggCharge')
            ->orderBy('id', 'desc')
            ->paginate(5);
        return response()->json([
            'data' => $engineers->items(),
            'links' => $engineers->links()
        ]);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => ""],
            ['name' => 'Engineers', 'url' => "/engg"],
            ['name' => 'Profile', 'url' => ""]
        ];


        $leaveApproved = EngineerLeave::where('engineer_id', $id)->where([
            'leave_approve_status' => 'approved',
        ])->sum('paid_leave_days');

        $leavePending = EngineerLeave::where('engineer_id', $id)->where([
            'leave_approve_status' => 'pending',
        ])->sum('paid_leave_days');

        $leaveCancelled = EngineerLeave::where('engineer_id', $id)->where([
            'leave_approve_status' => 'reject',
        ])->sum('paid_leave_days');



        $leaveBalance = LeaveBalance::where(['engineer_id' => $id])->first() ?? null;

        $freezed_balance = 0;
        if (!empty($leaveBalance)) {
            $freezed_balance = $leaveBalance->balance - $leaveBalance->freezed_leave_balance;
        } else {
            $freezed_balance = 0;
        }

        $dashCounts = [
            'pending_leaves' => (int)$leavePending,
            'approved_leaves' => (int)$leaveApproved,
            'declined_leaves' => (int)$leaveCancelled,
            'total_leaves' => (int)$leaveApproved + (int)$leavePending + (int)$leaveCancelled,
            'leave_balance' => $leaveBalance?->balance ?? 0,
            'freezed_balance' => $freezed_balance,
        ];


        $engineer = Engineer::with(['enggDoc', 'enggCharge', 'enggExtraPay', 'enggTravel', 'enggLang', 'enggPay', 'enggEdu', 'enggTicket', 'enggTicket.lead', 'enggRightToWork', 'enggTechCerty', 'enggSkills'])
            ->findOrFail($id);

        $engineerLeaves = EngineerLeave::where([
            'engineer_id' => $id
        ])
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.engineer.view', [
            'engineer'   => $engineer,
            'leaves' => $engineerLeaves,
            'breadcrumbs' => $breadcrumbs,
            'dashCounts' => $dashCounts
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        dd($request->toArray());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Engineer $engineer)
    {
        // dd($request->toArray());

        try {

            $validatedData = $request->validate([
                "engineer_id"       => "required|integer|exists:engineers,id",
                "hourly_charge"     => "required|numeric",
                "half_day_charge"   => "required|numeric",
                "full_day_charge"   => "required|numeric",
                "monthly_charge"    => "required|numeric",
            ]);

            // dd($validatedData);

            EngineerCharge::updateOrCreate(
                [
                    'engineer_id' =>  $validatedData['engineer_id']
                ],
                $validatedData
            );

            session()->flash('toast', [
                'type'    => 'success',
                'message' => 'Engineers Charges details updated successfully'
            ]);

            return redirect()->route('engg.index');
        } catch (\Exception $e) {

            session()->flash('toast', [
                'type'    => 'success',
                'message' => 'Opps, Something went wrong while updating Engineers Charges details, Try Again !!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $engineer = Engineer::findOrFail($id);

            // Delete related records only if they exist
            TicketWork::where('user_id', $id)->delete();
            EngineerPayout::where('engineer_id', $id)->delete();
            EngineerLeave::where('engineer_id', $id)->delete();
            EngineerEducation::where('user_id', $id)->delete();
            EngineerDocument::where('user_id', $id)->delete();
            EngineerCharge::where('engineer_id', $id)->delete();
            EngineerExtraPay::where('engineer_id', $id)->delete();
            EngineerSkill::where('user_id', $id)->delete();
            RightToWork::where('user_id', $id)->delete();
            EngineerLanguageSkill::where('user_id', $id)->delete();
            EngineerTravelDetail::where('user_id', $id)->delete();
            EngineerDailyWorkExpense::where('engineer_id', $id)->delete();
            EngineerIndustryExperience::where('user_id', $id)->delete();
            ModelsEngineerTechnicalCertification::where('user_id', $id)->delete();
            WorkBreak::where('engineer_id', $id)->delete();
            LeaveBalance::where('engineer_id', $id)->delete();

            // **Check if engineer exists before deleting**
            if ($engineer) {
                $engineer->delete(); // Use forceDelete() if soft deletes are enabled
            }

            return response()->json([
                'type'    => 'success',
                'message' => 'Engineer Removed Successfully.'
            ]);

            // session()->flash('toast', [
            //     'type'    => 'success',
            //     'message' => 'Engineer Removed Successfully.'
            // ]);

            // return redirect()->route('engg.index');
        } catch (\Exception $e) {
            return response()->json([
                'type'    => 'danger',
                'message' => 'Something went wrong.'
            ]);
            // session()->flash('toast', [
            //     'type'    => 'danger',
            //     'message' => 'Something went wrong, Please Try Again.',
            //     'error'   => $e->getMessage()
            // ]);

            // return redirect()->route('engg.index');
        }
    }



    public function engEdit()
    {

        // $breadcrumbs = [
        //     ['name' => 'Home', 'url' => ""],
        //     ['name' => 'Engineers', 'url' => "/engg"],
        // ];

        // $engineers = Engineer::with('enggCharge')->orderBy('id', 'desc')->get(); 

        return view('backend/engineer/edit', [
            // 'engineers'     => $engineers,
            // 'user'          => null, 
            // 'breadcrumbs'   => $breadcrumbs
        ]);
    }


    public function engJobDetailsUpdate(Request $request)
    {

        $request->validate([
            'engineer_id' => 'required|exists:engineers,id',
            'job_type' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'job_start_date' => 'required|date',
            'overtime' => 'required',
            'weekend' => 'required',
            'out_of_office_hour' => 'required',
            'public_holiday' => 'required',
            'currency_type' => 'required',
            'check_in_time' => 'required',
            'check_out_time' => 'required',
            'annual_leaves'  => 'required',
            'accumulated_leaves'  => 'required',

        ]);

        $engineerId = $request->engineer_id;

        Engineer::where([
            'id' => $engineerId
        ])->update([
            'job_type' => $request->job_type,
            'job_title' => $request->job_title,
            'job_start_date' => $request->job_start_date,
        ]);

        $engineer = Engineer::findOrFail($engineerId);

        EngineerCharge::updateOrCreate(
            ['engineer_id' => (int)$engineerId], // Conditions to find the record
            [
                'hourly_charge' => $request->hourly_charge,
                'half_day_charge' => $request->half_day_charge,
                'full_day_charge' => $request->full_day_charge,
                'monthly_charge' => $request->monthly_charge,
                'currency_type' => $request->currency_type,
                'check_in_time' => timezoneToUTC($request->check_in_time, $engineer->timezone),
                'check_out_time' => timezoneToUTC($request->check_out_time, $engineer->timezone),
                'annual_leaves'  => $request->annual_leaves,
                'accumulated_leaves'  => $request->accumulated_leaves,
            ]
        );

        EngineerExtraPay::updateOrCreate(
            ['engineer_id' => $engineerId], // Conditions to find the record
            [
                'overtime' => (float)$request->overtime,
                'out_of_office_hour' => (float)$request->out_of_office_hour,
                'weekend' => (float)$request->weekend,
                'public_holiday' => (float)$request->public_holiday,
                'status' => 1,
            ] // Values to update or insert
        );

        // Leave Allotment 
        // Monthly + Carry = Balance 
        $engineerLeaveBalance = LeaveBalance::where([
            'engineer_id' => $engineerId
        ])->first();

        if (empty($engineerLeaveBalance) && $request->job_type == 'full_time') {

            $currentYear = Carbon::now()->year;
            $currentMonth = Carbon::now()->month;
            $currentDay = Carbon::now()->day;

            $startDate = Carbon::parse($request->job_start_date);
            $startYear = $startDate->year;
            $startMonth = $startDate->month;

            // Early month bonus: Add 1 month if before 20th
            // $earlyMonthBonus = ($currentDay < 20) ? 1 : 0 ;

            $effectiveMonths = max(0, ($currentMonth - ($startMonth - 1)));

            // if ($startYear == $currentYear) {
            //     $totalLeaves = $request->accumulated_leaves + floor((($request->annual_leaves / 12)) * $effectiveMonths);
            // } else {
            //     $totalLeaves = $request->accumulated_leaves + floor((($request->annual_leaves / 12)) * ($currentMonth));
            // }

            // 2 + 1.66 = 3.66

            $monthsToConsider = ($startYear == $currentYear) ? $effectiveMonths : $currentMonth;

            // $per_month_leave = $leave_credited_this_month = floor(number_format((($request->annual_leaves / 12)), 2));
            $per_month_leave = number_format(floor(($request->annual_leaves / 12) * 100) / 100, 2);

            // $totalLeaves = $request->accumulated_leaves + bcdiv(($request->annual_leaves / 12), 1, 2) * $monthsToConsider;
            $totalLeaves = $request->accumulated_leaves + $per_month_leave;


            $leave_credited_this_month =  bcdiv(($request->annual_leaves / 12), 1, 2);


            $leaveBalanceData = [
                'engineer_id' => $engineerId,
                'total_leaves' => number_format($totalLeaves, 2),
                'used_leaves' => 0,
                'balance' => number_format($totalLeaves, 2),
                'leave_credited_this_month' => $leave_credited_this_month,
                'total_yearly_alloted' => (int)$request->annual_leaves,
                'total_paid_leave_used' => 0,
                'total_unpaid_leave_used' => 0,
                'opening_balance_from_past_year' => number_format($request->accumulated_leaves, 2)
            ];

            LeaveBalance::create($leaveBalanceData);
        }

        return redirect()->route('engg.show', $request->engineer_id);
    }

    public function EngineerSlip($engineerId, $payoutId)
    {
        $engineer = Engineer::with('enggCharge', 'enggPaymentDetail')->findOrFail($engineerId);
        $payout = EngineerPayout::findOrFail($payoutId); // assuming you have a Payout model
        $approvedLeaves = EngineerLeave::with('engineer')
            ->where('leave_approve_status', 'approved')
            ->where('engineer_id', $engineerId)
            ->get();
        $leaveBalance = LeaveBalance::where('engineer_id', $engineerId)->value('balance');;

        // Now calculate total paid and unpaid leave days
        $totalPaidLeave = $approvedLeaves->sum('paid_leave_days');
        $totalUnpaidLeave = $approvedLeaves->sum('unpaid_leave_days');

        $currencySymbols = [
            'dollar' => '$',
            'euro' => '€',
            'pound' => '£',
            'zloty' => 'zł',
        ];

        $engineerCurrency = isset($currencySymbols[$engineer->enggCharge->currency_type])
                ? $currencySymbols[$engineer->enggCharge->currency_type]
                : 'Unknown Currency';

        return view('backend.engineer.slip', compact('engineer', 'payout', 'totalPaidLeave', 'totalUnpaidLeave', 'leaveBalance' , 'engineerCurrency'));
    }



    public function EngineerSlipTest(Request $request)
    {
        return view('backend/engineer/slip_test');
    }

    public function EngineerWorkSlip($ticket_works_id)
    {
        $ids = explode(',', $ticket_works_id); // Convert string back to array
        $ticketWorks = TicketWork::whereIn('id', $ids)->With('engineer')->With('ticket')->With('engCharge')->get();

        // If not found, return an error blade view
        $reasonMessage = "This Engineer is Monthly Payout";
        if ($ticketWorks->isEmpty()) {
            return view('backend.errors.404',[
                'reasonMessage' => $reasonMessage
            ]);
        }

        $currencyMap = [
            'euro' => 'EUR',
            'pound' => 'GBP',
            'dollar' => 'USD',
            'zloty' => 'PLN',
        ];

        // Map currency names to short codes
        $exist = [];
        foreach ($ticketWorks as $work) {
            $work->currency_short = $currencyMap[strtolower($work->engCharge->currency_type)] ?? $work->engCharge->currency_type;
            $work->hourly_rate = (float) ($work->hourly_rate ?? 0);
            $work->total_work_time_count = $work->total_work_time;
            $work->total_work_time = (float) ($work->total_work_time ?? 0);
            $work->ZUS = 0.00;
            $work->PIT = 0.00;
            $zus_pit = EngineerPayout::whereJsonContains("ticket_work_ids", (string)$work->id)->first();
            if($zus_pit && !in_array($zus_pit->id, $exist))
            {
                $work->ZUS = $zus_pit->ZUS;
                $work->PIT = $zus_pit->PIT;
                $exist[] = $zus_pit->id;
            }
        }
        // dd($ticketWorks);
        return view('backend/engineer/work-slip', [
            'ticketWorks' => $ticketWorks,
        ]);
    }


    public function EngineerWorkSlipTest(Request $request)
    {
        // $ticket_works = TicketWork::with('engineer')->Where('user_id', $id)->get();
        // $engineer = Engineer::find($id);
        // dd($engineer);
        // dd($ticket_works[0]['engineer']['first_name']);

        return view(
            'backend/engineer/work-slip_test',
            [
                // 'ticket_works' => $ticket_works,
                // 'corruent_month' => $corruent_month,
                // 'engineer' => $engineer,
            ]
        );
    }


    public function testPushNotification()
    {

        $factory = (new Factory)->withServiceAccount(base_path('/public/ticky-app-b7897-firebase-adminsdk-fbsvc-b7334858e8.json'));
        $messaging = $factory->createMessaging();

        $deviceToken = 'dSrxOB9sRSakT-FR2RJhvs:APA91bE5IT8_rL4ktTvZfVbW7Fx3vqFBSrhWoxNXHqsITCck66zckQ_ROUAu9fRKqNZE8xNVgKBPf_9uoAejD-oIAPAkR5dymvOXiZHILC-XRfGeEbG9vZE';

        $notification = [
            'title' => 'Ticky | Ticket management',
            'body' => 'This is test notification.',
        ];

        $message = [
            'token' => $deviceToken,
            'notification' => $notification,
        ];

        try {
            $messaging->send($message);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function engineerByTimezone(Lead $lead)
    {
        $_engineers = Engineer::whereNotNull('timezone')->where('timezone', $lead->timezone)->get();
        $engineers = $_engineers->map(function ($engineer) {
            return [
                'name' => $engineer->first_name . ' ' . $engineer->last_name,
                'id' => $engineer->id,
                'profile' => $engineer->profile_image,
                'code' => $engineer->engineer_code,
                'job_type' => $engineer->job_type,
                'engineer_currency' => $engineer->enggCharge->currency_type,
            ];
        });

        $currencySymbols = config('currency.symbols');

        $html = view('backend.engineer.engineer_timezone', compact('engineers', 'currencySymbols'))->render();

        return response()->json([
            'html' => $html,
        ]);
    }
}
