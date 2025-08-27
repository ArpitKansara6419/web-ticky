<?php

namespace App\Http\Controllers;

use App\DataTables\TicketDataTable;
use App\Models\Customer;
use App\Models\DailyWorkNote;
use App\Models\Lead;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Engineer;
use App\Models\EngineerDailyWorkExpense;
use App\Models\TicketWork;
use App\Models\WorkBreak;
use App\Models\EngineerCharge;
use App\Models\EngineerPayout;
use App\Models\Holiday;
use App\Models\CustomerPayout;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\EngineerDropDownResource;
use App\Models\CustomerPayable;
use App\Models\EngineerExtraPay;
use PhpParser\Node\Expr\Cast\String_;
use Kreait\Firebase\Factory;
use PhpParser\Node\Expr\Empty_;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\returnSelf;
use App\Http\Controllers\Api\TicketController as ApiTicketController;
use App\Models\AppNotification;
use App\Services\NotificationServices;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    public $notificationServices;

    public $ticketService;

    public function __construct(
        NotificationServices $notificationServices,
        TicketService $ticketService
    )
    {
        $this->notificationServices = $notificationServices;
        $this->ticketService = $ticketService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => ""],
            ['name' => 'Tickets', 'url' => "/lead"],
        ];

        // $tickets = Ticket::with('customer', 'lead', 'engineer')->orderBy('id', 'desc')->get();

        $engineers = collect(Engineer::orderBy('id', 'desc')->get())->toArray();

        // dd($engineers);
        return view('backend.ticket.dataTable_list', [

            // 'tickets'         => $tickets,
            'breadcrumbs'     => $breadcrumbs,
            'engineers'       => $engineers

        ]);
        // return view('backend.ticket.index', [

        //     'tickets'         => $tickets,
        //     'breadcrumbs'     => $breadcrumbs,
        //     'engineers'       => $engineers

        // ]);
    }

    /**
     * DataTable loaded
     *
     * @param TicketDataTable $ticketDataTable
     * @return JsonResponse
     */
    public function dataTable(TicketDataTable $ticketDataTable) : JsonResponse
    {
        return $ticketDataTable->ajax();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $breadcrumbs = [
            ['name' => 'Home', 'url' => "/ticket"],
            ['name' => 'Tickets', 'url' => "/ticket"],
            ['name' => 'Create', 'url' => ""],
        ];

        $customers = Customer::all();
        // $_engineers = Engineer::get();
        // $engineers = $_engineers->map(function ($engineer) {
        //     return [
        //         'name' => $engineer->first_name . ' ' . $engineer->last_name,
        //         'id' => $engineer->id,
        //         'profile' => $engineer->profile_image,
        //         'code' => $engineer->engineer_code,
        //         'job_type' => $engineer->job_type,
        //         'engineer_currency' => $engineer->enggCharge->currency_type
        //     ];
        // });
        $engineers = [];
        $leads = Lead::where('is_ticket_created', '!=', 1)->get();
        $customerLeads = [];

        if (isset($_GET['customer_id'])) {
            $customerLeads = Lead::where(['customer_id' => $_GET['customer_id']])->get();
        }

        $currencySymbols = config('currency.symbols');
        return view('backend.ticket.form', [
            'customers'     => $customers,
            'leads'         => $leads,
            'engineers'     => $engineers,
            'breadcrumbs'   => $breadcrumbs,
            'customerLeads' => $customerLeads,
            'lead_id' => isset($_GET['lead_id']) ? $_GET['lead_id'] : '',
            'currencySymbols' => $currencySymbols
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {  
        // try{

            $validatedData = $request->validate([
                "id"                => "nullable|exists:tickets,id",
                "customer_id"       => "required|exists:customers,id",
                "engineer_id"       => "required|exists:engineers,id",
                "lead_id"           => "required|exists:leads,id",
                "client_name"       => "nullable|string",
                "task_start_date"    => "required|date",
                "task_end_date"      => "required|date",
                "task_time"         => "required",
                "task_name"         => "required|string",
                "scope_of_work"     => "nullable|string",
                "poc_details"       => "nullable|string",
                "re_details"        => "nullable|string",
                "call_invites"      => "nullable|string",
                "standard_rate"     => "required|numeric",
                "currency_type"     => "nullable|string",
                "travel_cost"       => "nullable|numeric",
                "tool_cost"         => "nullable|numeric",
                "rate_type"         => "nullable",
                "ref_sign_sheet"    => "nullable|file|mimes:pdf,jpg,png,doc,dox",
                "documents"         => "nullable|file|mimes:pdf,jpg,png,doc,dox",
                "food_expenses"     => "nullable|file",
                "misc_expenses"     => "nullable|file",
                'apartment'         => "nullable|string|max:70",
                'add_line_1'        => "nullable|string|max:255",
                'add_line_2'        => "nullable|string|max:255",
                'city'              => "nullable|string|max:50",
                'country'           => "nullable|string|max:50",
                'zipcode'           => "nullable|string|max:15",
                'latitude'          => "nullable",
                'longitude'        => "nullable",
                'timezone'          => "required",
                'tools'             => "nullable",
                'agreed_rate'       => 'required_if:rate_type,agreed|numeric|nullable',
                'engineer_agreed_rate' => 'nullable|numeric',
            ]);
    
            if($request->rate_type === 'agreed'){
                $validatedData['standard_rate'] = $request->agreed_rate;
            }
    
            if(empty($request->engineer_agreed_rate))
            {
                $validatedData['engineer_agreed_rate'] = 0;
            }
    
            $fcmFilePath = config('services.firebase.fcm_file');
    
            $task_time = Carbon::parse($validatedData['task_time'])->format('H:i:s');
            $task_start_date_time = timezoneToUTC($validatedData['task_start_date'].$task_time, $validatedData['timezone']);
            $task_end_date_time = timezoneToUTC($validatedData['task_end_date'].$task_time, $validatedData['timezone']);
    
            
    
            $validatedData['task_start_date'] = $task_start_date_time->copy()->format('Y-m-d');
            $validatedData['task_end_date'] = $task_end_date_time->copy()->format('Y-m-d');
            $validatedData['task_time'] = $task_start_date_time->copy()->format('H:i:s');
            /*$validatedData['task_start_date'] = Carbon::parse($validatedData['task_start_date'])->format('Y-m-d');
            $validatedData['task_end_date'] = Carbon::parse($validatedData['task_end_date'])->format('Y-m-d');
            $validatedData['task_time'] = Carbon::parse($validatedData['task_time'])->format('H:i:s');*/
            $validatedData['offered_notification_date_time'] = now();
    
            if (empty($request["id"])) {
                $lastTicket = Ticket::latest('id')->first();
                $lastCode = $lastTicket ? (int) str_replace('AIM-T-', '', $lastTicket->ticket_code) : 99;
                $nextCode = $lastCode + 1;
                $validatedData['ticket_code'] = 'AIM-T-' . $nextCode;
            }
    
            DB::beginTransaction();
    
            //handel the files form request
    
            $ticket = Ticket::updateOrCreate(
                [
                    'id' => $request["id"]
                ],
                $validatedData
            );
    
            Lead::findOrFail($request->lead_id)->update([
                'is_ticket_created' => 1,
            ]);
    
            $fileFields = ['ref_sign_sheet', 'documents', 'food_expenses', 'misc_expenses'];
    
            foreach ($fileFields as $field) {
    
                if ($request->hasFile($field)) {
                    $ext = $request->file($field)->getClientOriginalExtension();
                    $fileName = $ticket->id . '_' . $field . '_' . time() . '.' . $ext;
                    $path =  $request->file($field)->storeAs('ticket_docs', $fileName, 'public');
                    $ticket->update([$field => $path]);
                }
            }
    
            $message = $request['id'] ? 'Ticket details updated successfully' : 'Ticket added successfully.';
    
            session()->flash('toast', [
                'type'    => 'success',
                'message' => $message
            ]);
    
            $fetch_ticket = Ticket::with(['engineer', 'customer'])->where('id', $ticket->id)->first();
            $this->notificationServices->offeredTickets($fetch_ticket);
    
    
    
            DB::commit();
    
            return redirect()->route('ticket.index');
        // }catch(Exception $ex)
        // {

        //     return redirect()->route('ticket.index');
        // }
    }

    public function show(Ticket $ticket)
    {

        $ticket = Ticket::Where('id', $ticket->id)->with('customer')->with('engineer')->first();
        /*$ticket_works = TicketWork::select([
            'ticket_daily_work.*',
            DB::raw('SUM(TIME_TO_SEC(work_breaks.total_break_time)) as total_break_time_seconds')
        ])
        ->leftJoin('work_breaks', 'work_breaks.ticket_work_id', '=', 'ticket_daily_work.id')    
        ->with(['engineer', 'ticket', 'breaks'])
        ->where('ticket_daily_work.ticket_id', $ticket->id)
        ->groupBy('ticket_daily_work.id')
        ->get();*/
        $ticket_works = TicketWork::with(['engineer', 'ticket', 'breaks'])
                ->where('ticket_id', $ticket->id)
                ->withSum(['breaks as total_break_time_seconds' => function($query) {
                    $query->select(DB::raw('SUM(TIME_TO_SEC(total_break_time))'));
                }], '')
                ->orderBy('id', 'DESC')
                ->get();
        $lead = Lead::where([
            'id' => $ticket->lead_id
        ])->first();
        $breadcrumbs = [
            ['name' => 'Home', 'url' => "/ticket"],
            ['name' => 'Tickets', 'url' => "/ticket"],
            ['name' => 'Tickets Details', 'url' => "/"],
        ];

        $currencySymbols = [
            'dollar' => '$',
            'euro' => '€',
            'pound' => '£',
            'zloty' => 'zł',
        ];

        $engineerCharges = EngineerCharge::where([
            'engineer_id' => $ticket->engineer_id
        ])->first();

        return view(
            'backend.ticket.view',
            [
                'ticket' => $ticket,
                'lead' => $lead,
                'engineerCharges' => $engineerCharges,
                'ticket_works' => $ticket_works,
                'breadcrumbs' => $breadcrumbs,
                'ticket_currency' => $currencySymbols[$ticket->currency_type]
            ]
        );
    }

    public function edit(String $id)
    {
        try {
            $ticket = Ticket::with('customer')->findOrFail($id);
            $ticket->task_start_date = $ticket->ticket_start_date_tz;
            $ticket->task_end_date = $ticket->ticket_end_date_tz;
            $ticket->task_time = $ticket->ticket_time_tz;
            $customers = Customer::all();
            $_engineers = Engineer::where('timezone', $ticket->timezone)->orderBy('id', 'desc')->get();
            $engineers = $_engineers->map(function ($engineer) {
                return [
                    'name' => $engineer->first_name . ' ' . $engineer->last_name,
                    'id' => $engineer->id,
                    'profile' => $engineer->profile_image,
                    'code' => $engineer->engineer_code,
                    'job_type' => $engineer->job_type,
                    'engineer_currency' => $engineer->enggCharge->currency_type
                ];
            });
            $leads = Lead::all();
            $customerLeads = Lead::where([
                'customer_id' => $ticket->customer_id,
                'lead_status' => 'confirm'
            ])->get();
            $selectedLead = Lead::where([
                'id' => $ticket->lead_id,
            ])->first();
            $breadcrumbs = [
                ['name' => 'Home', 'url' => "/ticket"],
                ['name' => 'Tickets', 'url' => "/ticket"],
                ['name' => 'Create', 'url' => ""],
            ];
            $currencySymbols = config('currency.symbols');
            return view('backend/ticket/form', [
                'ticket'        => $ticket,
                'breadcrumbs'   => $breadcrumbs,
                'customers'     => $customers,
                'engineers'     => $engineers,
                'leads'         => $leads,
                'customerLeads' => $customerLeads,
                'selectedLead'  => $selectedLead,
                'currencySymbols' => $currencySymbols
            ])->with('toast', [
                'type' => 'success',
                'message' => 'Ticket data fetched successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching ticket details for ID ' . $id . ': ' . $e->getMessage());
            return back()->with('toast', [
                'type'    => 'warning',
                'message' => 'Something went wrong while fetching ticket data, try again'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $ticket = Ticket::findOrFail($id);
            Lead::where([
                'id' => $ticket->lead_id
            ])->update([
                'is_ticket_created' => 0
            ]);
            $ticket->delete();
            session()->flash('toast', [
                'type'    => 'success',
                'message' => 'Ticket Removed Successfully.'
            ]);

            $ticketWorks = TicketWork::where([
                'ticket_id' => $id
            ])->get();

            foreach ($ticketWorks as $key => $value) {
                $engineerPayouts = EngineerPayout::whereJsonContains('ticket_work_ids', $value->id)->get();
                foreach ($engineerPayouts as $payout) {
                    $ids = json_decode($payout->ticket_work_ids, true); // Convert JSON to array
                    if (($key = array_search($value->id, $ids)) !== false) {
                        unset($ids[$key]); // Remove the ID
                    }
                    $payout->ticket_work_ids = array_values($ids); // Re-index array
                    $payout->save(); // Update the record
                }

                DailyWorkNote::where([
                    'work_id' => $value->id
                ])->delete();
            }

            WorkBreak::where([
                'ticket_id' => $id
            ])->delete();

            $customerPayable = CustomerPayable::where([
                'ticket_id' => $id
            ])->get();

            foreach ($customerPayable as $key => $value) {
                $custmorePayouts = CustomerPayout::whereJsonContains('customer_payable_ids', $value->id)->get();
                foreach ($custmorePayouts as $payout) {
                    $ids = json_decode($payout->customer_payable_ids, true); // Convert JSON to array
                    if (($key = array_search($value->id, $ids)) !== false) {
                        unset($ids[$key]); // Remove the ID
                    }
                    $payout->customer_payable_ids = array_values($ids); // Re-index array
                    $payout->save();
                }
            }

            // Delete Ticket Work
            TicketWork::where([
                'ticket_id' => $id
            ])->delete();

            // Delete customer Payable
            CustomerPayable::where([
                'ticket_id' => $id
            ])->delete();

            // Other expense delete
            EngineerDailyWorkExpense::Where([
                'ticket_id' => $id
            ])->delete();

            // return redirect()->route('ticket.index');

            return response()->json([
                'status' => true,
                'message' => 'Ticket Removed Successfully.'
            ]);

            // CustomerPayout // work_id
            // DailyWorkNote -> work_id
        } catch (\Exception $e) {

            // session()->flash('toast', [
            //     'type'    => 'danger',
            //     'message' => 'Something went wrong, Please Try Again.',
            //     'error'   => $e->getMessage()
            // ]);

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong, Please Try Again.'
            ]);

            // return redirect()->route('ticket.index');
        }
    }

    public function ChangeTicketEng(Request $request)
    {
        // dd($request->all());

        try {

            $updateData['engineer_id'] = $request->engineer;
            $ticket = Ticket::findOrFail($request->ticket_id)->update($updateData);
            session()->flash('toast', [
                'type'    => 'success',
                'message' => 'Engineer updated Successfully.'
            ]);
            return redirect()->route('ticket.index');
        } catch (\Exception $e) {
            session()->flash('toast', [
                'type'    => 'danger',
                'message' => 'Something went wrong, Please Try Again.',
                'error'   => $e->getMessage()
            ]);
            return redirect()->route('ticket.index');
        }
    }

    public function OvertimeIndex(Request $request)
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => ""],
            ['name' => 'Overtime', 'url' => "/overtime"],
        ];
        $ticket_works = TicketWork::with(['engineer', 'ticket', 'ticket.customer'])->Where('is_overtime', 1)->get();

        // dd($ticket_works);
        return view('backend.overtime.index', [
            'ticket_works' => $ticket_works,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function overtimeStatusUpdate(Request $request)
    {
        $ticketWork = TicketWork::findOrFail($request->overtime_id);
        $engineerId = $ticketWork->user_id;
        $deviceToken = Engineer::findOrFail($engineerId)->device_token;
        $fcmFilePath = config('services.firebase.fcm_file');

        $updateData['is_overtime_approved'] = $request->overtimeve_approve_status;

        $ticket = TicketWork::findOrFail($request->overtime_id)->update($updateData);

        if ($request->overtimeve_approve_status == 1) {

            $factory = (new Factory)->withServiceAccount(base_path("/public/" . $fcmFilePath));
            $messaging = $factory->createMessaging();

            $notification = [
                'title' => 'Overtime Approved 🕒',
                'body' => "Your overtime request has been approved. Thank you for your dedication!",
            ];

            $message = [
                'token' => $deviceToken,
                'notification' => $notification,
            ];

            $messaging->send($message);
        }

        return redirect()->route('overtime.index');
    }

    public function ticketInvoice(String $id)
    {
        $ticket_works = TicketWork::Where('id', $id)->with('engineer')->get();

        // dd($ticket_work);
        return view('backend/ticket/ticket-invoice', [
            'ticket_works' => $ticket_works
        ]);
    }

    public function getWorkNotes(String $id)
    {
        $ticket_notes = DailyWorkNote::where('work_id', $id)->get();
        $work_breaks = WorkBreak::where('ticket_work_id', $id)->get();
        return response()->json([
            'ticket_notes' => $ticket_notes,
            'work_breaks' => $work_breaks,
        ]);
    }

    public function getWorkExpense($workId)
    {
        $expanse = EngineerDailyWorkExpense::Where('ticket_work_id', $workId)->get();

        return response()->json([
            'expanse' => $expanse,
        ]);
    }

    public function otherpayUpdate(Request $request)
    {

        $updateData['other_pay'] = $request->other_pay;

        $ticket = TicketWork::findOrFail($request->ticket_work_id)->update($updateData);
        return redirect()->back()->with('success', 'Daily payout updated successfully!');
    }

    public function workStatusUpdate(Request $request)
    {
        // Assuming you have a Ticket model with columns for these statuses
        $ticket = TicketWork::findOrFail($request->workId); // Modify this as per your actual logic (like finding by ID)

        if ($ticket) {
            switch ($request->switch_type) {
                case 'ooh-switch':
                    $ticket->is_out_of_office_hours = $request->status;
                    break;
                case 'ww-switch':
                    $ticket->is_weekend = $request->status;
                    break;
                case 'hw-switch':
                    $ticket->is_holiday = $request->status;
                    break;
                case 'ot-switch':
                    $ticket->is_overtime = $request->status;
                    break;
                default:
                    return response()->json(['success' => false, 'message' => 'Invalid switch type'], 400);
            }

            $ticket->save();
            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Ticket not found'], 404);
    }

    public function fetchPopup(Request $request)
    {
        // Get the ticket ID from the request
        $ticketWorkId = $_GET['id'];

        $ticket_works = TicketWork::select('*')
            ->with('ticket')->find($ticketWorkId);

        $totalBreakTime = WorkBreak::where('ticket_work_id', $ticketWorkId)
            ->selectRaw("SEC_TO_TIME(SUM(TIME_TO_SEC(total_break_time))) as total_break_time")
            ->value('total_break_time');

        if (!empty($totalBreakTime)) {
            // Convert strings to Carbon instances
            $totalWorkTimeObj = Carbon::createFromFormat('H:i:s', $ticket_works->total_work_time);
            $totalBreakTimeObj = Carbon::createFromFormat('H:i:s', $totalBreakTime);

            // Subtract break time from total work time
            $totalWorkingTime = $totalWorkTimeObj->diff($totalBreakTimeObj)->format('%H:%I:%S');
        } else {
            $totalBreakTime = '00:00:00';
            $totalWorkingTime =  $ticket_works->total_work_time;
        }


        if (!$ticket_works) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }

        // Fetch the ticket details
        $ticketWorks = TicketWork::select('*')
            ->Where('id', $ticketWorkId)->first();

        $ticket = Ticket::find($ticketWorks->ticket_id);

        $currencySymbols = [
            'dollar' => '$',
            'euro' => '€',
            'pound' => '£',
            'zloty' => 'zł',
        ];

        // Get the correct currency symbol (default to $ if currency_type is missing)
        $ticket_currency = isset($ticket_works->ticket) ?
            ($currencySymbols[$ticket_works->ticket->currency_type] ?? '$') : '$';

        $engineerRates = EngineerCharge::where([
            'engineer_id' => $ticket->engineer_id
        ])->first();

        $engineerExtraPay =  EngineerExtraPay::where([
            'engineer_id' => $ticket->engineer_id
        ])->first();

        $otherExpenses = EngineerDailyWorkExpense::where([
            'ticket_work_id' => $ticketWorkId
        ])->get();

        $engineerCurrency = $currencySymbols[$engineerRates->currency_type];

        $ticketNotesAttachments = DailyWorkNote::where('work_id', $ticketWorkId)->get();

        $engineer = Engineer::find($ticket->engineer_id);

        $ticket_breaks = WorkBreak::where('ticket_id', $ticket->id)
                    ->where('ticket_work_id', $ticketWorkId)
                    ->get();

        $html = view(
            'backend.payout.components.eng_payout_ticket_detail_popup',
            // 'backend.ticket.components.ticket_detail_popup',
            compact(
                'ticket',
                'ticket_works',
                'ticket_currency',
                'engineerCurrency',
                'engineerExtraPay',
                'engineerRates',
                'otherExpenses',
                'ticketNotesAttachments',
                'totalBreakTime',
                'totalWorkingTime',
                'ticketWorkId',
                'engineer',
                'ticket_breaks',
            )
        )->render();

        return response()->json(['html' => $html]);
    }

    public function updateAmount(Request $request)
    {
        // dd($request->all());
        if ($request->type == 'customer') {
            // Validate request
            $request->validate([
                'payoutId' => 'required', // Ensure workId exists
                'key'    => 'required|string', // Ensure key is a valid column
                'amount' => 'required|numeric' // Ensure amount is a number
            ]);

            $payout = CustomerPayable::findOrFail($request->payoutId);

            $amount = $request->amount;

            if($request->key === 'ww_payable' && !empty($amount))
            {
                $payout->is_weekend = 1;
            }else if($request->key === 'ww_payable' && empty($amount)){
                $payout->is_weekend = 0;
            }

            if($request->key === 'hw_payable' && !empty($amount))
            {
                $payout->is_holiday = 1;
            }else if($request->key === 'hw_payable' && empty($amount)){
                $payout->is_holiday = 0;
            }

            if($request->key === 'ot_payable' && !empty($amount))
            {
                $payout->is_overtime = 1;
            }else if($request->key === 'ot_payable' && empty($amount)){
                $payout->is_overtime = 0;
            }

            if($request->key === 'ooh_payable' && !empty($amount))
            {
                $payout->is_out_of_office_hours = 1;
            }else if($request->key === 'ooh_payable' && empty($amount)){
                $payout->is_out_of_office_hours = 0;
            }

            // Subtract old value from client_payable
            $oldValue = $payout->{$request->key};
            $payout->client_payable -= $oldValue;

            // Update the requested field
            $payout->{$request->key} = $request->amount;

            // Add new value to client_payable
            $payout->client_payable += $request->amount;

            // Save once after all calculations
            $payout->save();
        } else {
            // Validate request for TicketWork
            $request->validate([
                'workId' => 'required|exists:ticket_daily_work,id', // Ensure workId exists
                'key'    => 'required|string', // Ensure key is a valid column
                'amount' => 'required|numeric' // Ensure amount is a number
            ]);

            $ticket = TicketWork::findOrFail($request->workId);

            $key = $request->key;
            $amount = $request->amount;

            if($request->key === 'weekend_payable' && !empty($amount))
            {
                $ticket->is_weekend = 1;
            }else if($request->key === 'weekend_payable' && empty($amount)){
                $ticket->is_weekend = 0;
            }

            if($request->key === 'holiday_payable' && !empty($amount))
            {
                $ticket->is_holiday = 1;
            }else if($request->key === 'holiday_payable' && empty($amount)){
                $ticket->is_holiday = 0;
            }

            if($request->key === 'overtime_payable' && !empty($amount))
            {
                $ticket->is_overtime = 1;
            }else if($request->key === 'overtime_payable' && empty($amount)){
                $ticket->is_overtime = 0;
            }

            if($request->key === 'out_of_office_payable' && !empty($amount))
            {
                $ticket->is_out_of_office_hours = 1;
            }else if($request->key === 'out_of_office_payable' && empty($amount)){
                $ticket->is_out_of_office_hours = 0;
            }

            // If the key is not "other_pay", adjust daily_gross_pay
            if ($key !== 'other_pay') {
                $oldValue = $ticket->{$key};
                $ticket->daily_gross_pay -= $oldValue;
                $ticket->{$key} = $amount;
                $ticket->daily_gross_pay += $amount;
            } else {
                // Just update the other_pay field without touching daily_gross_pay
                $ticket->other_pay = $amount;
            }

            // Save once after all calculations
            $ticket->save();
        }

        return response()->json(['success' => "Data updated successfully"]);
    }

    public function testTicketEndWork()
    {

        $ticketWorks = TicketWork::whereNotNull('work_end_date')
            ->whereNotNull('end_time')
            ->get();

        foreach ($ticketWorks as $key => $value) {

            $validatedData = [
                'ticket_work_id' => $value->id,
                'end_time' => $value->end_time,
                'work_end_date'  => $value->work_end_date,
                'note' => '',
                'status' => $value->status,
                'document_file' => '',
            ];

            /// ---------------- Engineer Payout Calculations ------------------

            $ticketWork = TicketWork::findOrFail($validatedData['ticket_work_id']);
            $ticket = Ticket::findOrFail($ticketWork->ticket_id);

            // Assuming you have 'start_date' and 'end_date' along with 'start_time' and 'end_time'
            $start = Carbon::parse($ticketWork->work_start_date . ' ' . $ticketWork->start_time);
            $end = Carbon::parse($validatedData['work_end_date'] . ' ' . $validatedData['end_time']);

            $totalBreakTimeInSeconds = WorkBreak::where('ticket_work_id', $validatedData['ticket_work_id'])
                ->selectRaw("SUM(TIME_TO_SEC(total_break_time)) as total_break_time")
                ->value('total_break_time');

            $totalBreakSeconds = $totalBreakTimeInSeconds ? $totalBreakTimeInSeconds : 0;

            // Adjust the end time by subtracting break duration
            $adjustedEnd = $end->copy()->subSeconds($totalBreakSeconds);

            $adjustedEnd->format('Y-m-d H:i:s');

            $end = $adjustedEnd;

            if ($end->lessThanOrEqualTo($start)) {
                return response()->json([
                    'success' => false,
                    'message' => 'End time must be after start time.',
                ], 400);
            }

            // DB::transaction(function () use ($ticketWork, $start, $end, $ticket, $request, $validatedData) {

            $workDate = $ticketWork->work_start_date;

            $isHoliday = Holiday::where('date', $workDate)->exists();
            $isWeekend = Carbon::parse($workDate)->isWeekend();

            // Calculate Engineer Payout
            (new ApiTicketController)->calculateEngineerPayout(
                $start,
                $end,
                $ticket,
                $ticketWork,
                $validatedData,
                null,
                $isHoliday,
                $isWeekend
            );

            // Calculate Customer Payable
            (new ApiTicketController)->calculateCustomerPayable(
                $start,
                $end,
                $ticket,
                $ticketWork,
                $validatedData,
                $isHoliday,
                $isWeekend
            );

            // update ticket status
            $ticket->update(['status' => $validatedData['status']]);
        }
    }

    public function editTicketWork(TicketWork $ticketWork)
    {   
        // $v = collect($ticketWork->select('id', 'ticket_id', 'work_start_date', 'work_end_date', 'start_time', 'end_time'));
        // dd($v->toArray());
        // $ticket_work = TicketWork::where('id', $ticketWork->id)->select('id', 'ticket_id', 'work_start_date', 'work_end_date', 'start_time', 'end_time')->first();
        $start_time = $ticketWork->start_time_timezone->format('H:i');
        $end_time = $ticketWork->end_time_timezone->format('H:i');
        return response()->json([
            'success' => true,
            'message' => 'Work EDITED!',
            'data' => $ticketWork,
            'start_time' => $start_time,
            'end_time' => $end_time,
        ], 200);
    }

    public function updateTicketWorkAdjust(TicketWork $ticketWork, Request $request)
    {
        /**
         * Remove Ticket Breaks
         */
        WorkBreak::where('ticket_work_id', $ticketWork->id)->delete();

        $ticket = Ticket::find($ticketWork->ticket_id);

        /**
         * TIME CONVERT TO UTC
         */
        $start_date_time =  timezoneToUTC($ticketWork->work_start_date_timezone->format('Y-m-d') . ' ' . $request->start_time, $ticket->timezone); 
        $end_date_time =  timezoneToUTC($ticketWork->work_start_date_timezone->format('Y-m-d') . ' ' . $request->end_time, $ticket->timezone); 

        // dd([
        //     'start_date_time' => $start_date_time,
        //     'end_date_time' => $end_date_time
        // ]);

        $ticketWork->update([
            'work_start_date'=> $start_date_time->format('Y-m-d'),
            'work_end_date'=> $end_date_time->format('Y-m-d'),
            'start_time'=> $start_date_time->format('H:i:s'),
            'end_time'=> $end_date_time->format('H:i:s'),
        ]);

        $ticketWork = TicketWork::find($ticketWork->id);
        // dd($ticketWork);

        $this->ticketService->closeTicket($ticket, $ticket->status, $ticketWork);

        return response()->json([
            'status' => true,
            'message' => 'Work Updated!',
            'data' => $ticketWork
        ]);
    }

    public function storeNewWorkTicket(Ticket $ticket, Request $request)
    {
        /**
         * 1. Check valid work date between ticket start & end
         * 
         * 2. CHECK WORK Date
         * If exist then do not give permission to create
         * 
         * 3. Finale ADJUST remaining
         */

        try{
            // dd($ticket);
            DB::beginTransaction();
            $request_start_date_time = timezoneToUTC($request->start_date. ' '. $request->start_time, $ticket->timezone);
            $request_end_date_time = timezoneToUTC($request->start_date. ' '. $request->end_time, $ticket->timezone);

            // dd([
            //     'ticket_start' => $ticket->task_start_date,
            //     'ticket_end'=> $ticket->task_end_date,
            //     'request_start' => $request_start_date_time->copy()->format('Y-m-d'),
            //     'request_end' => $request_end_date_time->copy()->format('Y-m-d'),
            //     'condition1' => $ticket->task_start_date < $request_start_date_time->copy()->format('Y-m-d'),
            //     'condition2' => $ticket->task_end_date > $request_end_date_time->copy()->format('Y-m-d'),
            // ]);

            $ticket_start_date = Carbon::parse($ticket->task_start_date);
            $ticket_end_date = Carbon::parse($ticket->task_end_date);

            // dd([
            //     'ticket_start_date' => $ticket_start_date->copy()->format('Y-m-d'),
            //     'ticket_end_date' => $ticket_end_date->copy()->format('Y-m-d'),
            //     'request_start_date' => $request_start_date_time->copy()->format('Y-m-d'),
            //     'request_end_date' => $request_end_date_time->copy()->format('Y-m-d'),
            // ]);

            $start = $request_start_date_time->copy()->format('Y-m-d');
            $end = $request_end_date_time->copy()->format('Y-m-d');

            // dd([
            //     'start' => $start,
            //     'end' => $end,
            //     'ticket_start_date' => $ticket_start_date->copy()->format('Y-m-d'),
            //     'ticket_end_date' => $ticket_end_date->copy()->format('Y-m-d'),
            //     'condition1' =>  $end > $ticket_end_date->copy()->format('Y-m-d')
            // ]);
    
            // Now check the date exist in ticket task_start_date & ticket_end_date : HERE compare only date
            if(
                $start < $ticket_start_date->copy()->format('Y-m-d') ||
                $end > $ticket_end_date->copy()->format('Y-m-d')
            ){
                return response()->json([
                    'status' => false,
                    'message' => 'Work Date is not valid!',
                ], 400);
            }

            // NOW check TicketWork work_start_date & work_end_date remember check  only date that exist in work_start_date or not
            $check = TicketWork::where('ticket_id', $ticket->id)->whereDate('work_start_date', $request_start_date_time->copy()->format('Y-m-d'))->first();
            if($check){
                return response()->json([
                    'status' => false,
                    'message' => 'Work Date is already exist!',
                ], 400);
            }

            // $engineerCharge = EngineerCharge::where('engineer_id', $ticket->engineer_id)->first();

            $ticketStartData = [
                'ticket_id' => $ticket->id,
                'work_start_date'=> $request_start_date_time->copy()->format('Y-m-d'),
                'work_end_date'=> $request_end_date_time->copy()->format('Y-m-d'),
                'start_time'=> $request_start_date_time->copy()->format('H:i:s'),
                'end_time'=> $request_end_date_time->copy()->format('H:i:s'),
                'user_id'=> $ticket->engineer_id,
                'note'=> "ADJUSTED TIME BY ADMIN",
                'travel_cost'=> 0,
                'tool_cost'=> 0,
                'status'=> $request->ticket_status,
            ];

            $ticketStartData['is_out_of_office_hours'] = 0;

            $ticketWork = TicketWork::create($ticketStartData);

            unset($ticketStartData['user_id']);
            unset($ticketStartData['start_time']);

            

            $ticketStartData['ticket_work_id'] = $ticketWork->id;
            $ticketStartData['engineer_id'] = $ticket->engineer_id;
            $ticketStartData['customer_id'] = $ticket->customer_id;
            $ticketStartData['work_start_time'] = $ticketWork->start_time;
            $ticketStartData['work_end_time'] = $ticketWork->end_time;
            $ticketStartData['currency'] = $ticketWork->currency;

            // dd($ticketStartData);

            CustomerPayable::create($ticketStartData);

            $this->ticketService->closeTicket($ticket, $ticket->status, $ticketWork);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Successfully Added!',
            ], 200);
        }catch(Exception $ex){
            DB::rollBack();
            return response()->json([
                'status' => false,
                // 'message' => 'Something went wrong!',
                'message' => $ex->getMessage(),
            ], 500);
        }


    }
}
