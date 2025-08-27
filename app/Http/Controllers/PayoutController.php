<?php

namespace App\Http\Controllers;

use App\Models\CustomerPayable;
use App\Models\CustomerPayout;
use App\Models\DailyWorkNote;
use App\Models\Engineer;
use App\Models\TicketWork;
use App\Models\EngineerPayout;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Models\EngineerCharge;
use App\Models\EngineerDailyWorkExpense;
use App\Models\EngineerExtraPay;
use App\Models\Ticket;
use App\Models\WorkBreak;
use Google\Cloud\Storage\Connection\Rest;
use Illuminate\Support\Facades\Log;

class PayoutController extends Controller
{
    public function index(Request $request)
    {

        $breadcrumbs = [
            ['name' => 'Home', 'url' => "/engineer-payouts"],
            ['name' => 'Engineer Payout', 'url' => "/engineer-payouts"],
            ['name' => 'Engineer Payslip', 'url' => "/payout"],
        ];

        $payouts = EngineerPayout::where('engineer_id', $request->engineer)->with('engineer', 'engineer.enggCharge')->orderBy('id', 'desc')->get();
        $engineer = Engineer::find($request->engineer);
        return view('backend.payout.index', [
            'breadcrumbs' => $breadcrumbs,
            'payouts' => $payouts,
            'engineer' => $engineer
        ]);
    }

    public function show($id)
    {

        $breadcrumbs = [
            ['name' => 'Home', 'url' => "/engineer-payouts"],
            ['name' => 'Engineer Payout', 'url' => "/engineer-payouts"],
            ['name' => 'Payout', 'url' => "/payout"],
        ];

        $currentMonth = now()->month;

        if (isset($_GET['month'])) {
            $currentMonth = (int) $_GET['month']; // Cast to integer for safety
        }



        $engineer = Engineer::with(['enggDoc', 'enggCharge', 'enggExtraPay', 'enggTravel', 'enggLang', 'enggPay', 'enggEdu', 'enggTicket', 'enggRightToWork', 'enggTechCerty', 'enggSkills'])
            ->findOrFail($id);

        $filter = request()->get('filter'); // Get filter value from URL

        $daily_works = TicketWork::where('user_id', $id) // Filter by user ID
            ->whereNotNull('work_end_date')
            ->whereNotNull('end_time')
            ->when($filter === 'paid', function ($query) {
                return $query->where('engineer_payout_status', 'paid'); // Show only "paid"
            })
            ->when($filter === 'unpaid', function ($query) {
                return $query->where('engineer_payout_status', '!=', 'paid'); // Show only "unpaid"
            })
            ->when($currentMonth, function ($query) use ($currentMonth) {
                return $query->whereMonth('work_start_date', $currentMonth); // Apply month filter
            })
            ->with('ticket', 'ticket.customer')
            ->get();

        $_engineers = Engineer::get();

        $engineers = $_engineers->map(function ($engineer) {
            return [
                'name' => $engineer->first_name . ' ' . $engineer->last_name,
                'id' => $engineer->id,
            ];
        });

        $currencySymbols = [
            'dollar' => '$',
            'euro' => '€',
            'pound' => '£',
            'zloty' => 'zł',
        ];

        // Generate Month Options
        $month = [
            ['name' => 'January', 'value' => 1],
            ['name' => 'February', 'value' => 2],
            ['name' => 'March', 'value' => 3],
            ['name' => 'April', 'value' => 4],
            ['name' => 'May', 'value' => 5],
            ['name' => 'June', 'value' => 6],
            ['name' => 'July', 'value' => 7],
            ['name' => 'August', 'value' => 8],
            ['name' => 'September', 'value' => 9],
            ['name' => 'October', 'value' => 10],
            ['name' => 'November', 'value' => 11],
            ['name' => 'December', 'value' => 12],
        ];

        $engineerExtraPay = EngineerExtraPay::where(
            ['engineer_id' =>  $engineer->id]
        )->first();

        return view('backend.payout.engineer_payout', [
            'breadcrumbs' => $breadcrumbs,
            'engineer' => $engineer,
            'engineer_currency' => isset($currencySymbols[$engineer->enggCharge->currency_type])
                ? $currencySymbols[$engineer->enggCharge->currency_type]
                : 'Unknown Currency',
            'daily_works' => $daily_works,
            'engineers' => $engineers,
            'selectedEngineerId' => $id,
            'currentMonth' => $currentMonth,
            'month' => $month
        ]);
    }

    public function store(Request $request)
    {
        $requestData = $request->all();
        DB::beginTransaction();

        $selectedMonth = request('selected_month'); // e.g., "4"
        $year = now()->year; // current year

        $formattedMonth = $year . '-' . str_pad($selectedMonth, 2, '0', STR_PAD_LEFT);
        $payoutMonth = $formattedMonth;

        // Determine monthly payout status (true or false based on checkbox value)
        $isMonthly = isset($requestData['monthly-pay']) && $requestData['monthly-pay'] === 'true';

        if (isset($requestData['daily_work_ids']) &&  !empty($requestData['daily_work_ids'])) {
            foreach ($requestData['daily_work_ids'] as $key => $value) {
                TicketWork::where([
                    'id' => $value
                ])->update([
                    'engineer_payout_status' => 'paid'
                ]);
            }
        }

        $engineerRates = EngineerCharge::where('engineer_id', $requestData['engineer_id'])->first();

        if ($isMonthly) {
            $engineer = Engineer::findOrFail($requestData['engineer_id']);

            // Decode existing monthly_payout or start fresh
            $decoded = json_decode($engineer->monthly_payout, true);
            $monthlyPayout = is_array($decoded) ? $decoded : [];

            // Add or update the current selected month
            $monthlyPayout[$formattedMonth] = true;

            // Save updated data
            $engineer->monthly_payout = json_encode($monthlyPayout);
            $engineer->save();
        }





        EngineerPayout::create([
            'engineer_id' => $requestData['engineer_id'],
            'ticket_work_ids' => $requestData['daily_work_ids'] ?? json_encode([]),
            'total_payable' => $requestData['total_payable'],
            'extra_incentive' => 0,
            'gross_pay' => $requestData['total_payable'],
            'currency' => $engineerRates->currency_type,
            'note' => 'none',
            'payment_date' => now()->format('Y-m-d'),
            'payment_type' => 'bank_transfer',
            'payout_type'        => $isMonthly ? 'monthly' : 'daily',
            'payout_month'     => $payoutMonth,
            'ZUS' => $requestData['total_zus'],
            'PIT' => $requestData['total_pit'],
        ]);


        session()->flash('toast', [
            'type'    => 'success',
            'message' => 'Engineer payout created successfully.'
        ]);

        DB::commit();

        // return redirect()->route('payout.index');
        return redirect()->route('payout.index', ['engineer' => $requestData['engineer_id'], 'filter' => 'all']);
    }

    public function destroy(string $id)
    {
        try {
            $engineerPayout = EngineerPayout::findOrFail($id);

            // Handle the ticket_work_ids safely
            $ticketWorkIds = $engineerPayout->ticket_work_ids;

            // Check if $ticketWorkIds is already an array or a JSON string
            if (is_string($ticketWorkIds)) {
                $ticketWorkIds = json_decode($ticketWorkIds, true) ?? [];
            }

            $ticketDailyWorks = TicketWork::whereIn('id', $ticketWorkIds)->get();

            foreach ($ticketDailyWorks as $ticketWork) {
                $ticketWork->update(['engineer_payout_status' => 'pending']);
            }


            if ($engineerPayout->payout_type === 'monthly') {
                $engineer = Engineer::find($engineerPayout->engineer_id);
                if ($engineer) {
                    $monthKey = $engineerPayout->payout_month; // <-- accurate month now

                    $currentPayouts = json_decode($engineer->monthly_payout, true) ?? [];

                    if (isset($currentPayouts[$monthKey])) {
                        unset($currentPayouts[$monthKey]);
                        $engineer->monthly_payout = json_encode($currentPayouts);
                        $engineer->save();
                    }
                }
            }



            // Delete the EngineerPayout record
            $engineerPayout->delete();

            // Flash success message
            session()->flash('toast', [
                'type'    => 'success',
                'message' => 'Payout Deleted Successfully.'
            ]);

            return redirect()->back()->with('success', 'Payout Deleted successfully.');
        } catch (\Exception $e) {
            // Flash error message
            session()->flash('toast', [
                'type'    => 'danger',
                'message' => 'Something went wrong, Please Try Again.',
                'error'   => $e->getMessage()
            ]);

            return redirect()->route('payout.index');
        }
    }

    public function dailyPayoutUpdate(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'ticket_work_id' => 'required|exists:ticket_daily_work,id', // Ensure the ID exists in the table
            'travel_cost' => 'nullable|numeric',
            'tool_cost' => 'nullable|numeric',
            'daily_total_payable' => 'nullable|numeric',
            'overtime' => 'nullable|string',
        ]);

        // Fetch the existing record
        $ticketWork = TicketWork::find($request->ticket_work_id);

        // Update only the fields provided in the request, keeping others unchanged
        $ticketWork->update([
            'travel_cost' => $request->travel_cost ?? $ticketWork->travel_cost,
            'tool_cost' => $request->tool_cost ?? $ticketWork->tool_cost,
            'hourly_payable' => $request->daily_total_payable ?? $ticketWork->daily_total_payable,
            'overtime_hour' => $request->overtime ?? $ticketWork->overtime,
        ]);

        // Return success response or redirect
        return redirect()->back()->with('success', 'Daily payout updated successfully!');
    }

    public function getTicketData($id)
    {
        $dailyWorkData = TicketWork::selectRaw('id, user_id, ticket_id, user_id, work_start_date, total_work_time, overtime_payable, out_of_office_payable,weekend_payable, holiday_payable, overtime_hour, travel_cost, tool_cost, hourly_payable, is_out_of_office_hours, is_weekend, is_holiday, is_overtime')
            ->with('workExpense')
            ->with('engCharge')
            ->with('ticket')
            ->where('id', $id)
            ->first();

        $engineerExtraPay = EngineerExtraPay::where(
            ['engineer_id' =>  $dailyWorkData->user_id]
        )->first();

        return response()->json([
            'status' => 'success',
            'daily_work_data' => $dailyWorkData,
            'extra_pay' => $engineerExtraPay
        ], 200);
    }

    public function getCustomerTicketData($id)
    {

        $dailyWorkData = TicketWork::selectRaw('id, user_id, user_id, work_start_date, total_work_time, hourly_payable, overtime_hour, travel_cost, tool_cost, hourly_payable, is_out_of_office_hours, is_weekend, is_holiday, is_overtime')
            ->with('workExpense')
            ->with('engCharge')
            ->where('id', $id)
            ->first();

        // $engineerExtraPay = CustomerPayable::where(
        //         [ 'engineer_id' =>  $dailyWorkData->user_id ]
        // )->first();

        $customerPayable = CustomerPayable::with('engCharge')->where(
            ['id' =>  $id]
        )->first();

        return response()->json([
            'status' => 'success',
            'daily_work_data' => $dailyWorkData,
            'customer_payable' => $customerPayable
        ], 200);
    }

    public function payoutSlip(String $id)
    {
        try {
            // Find the EngineerPayout record by ID or throw an exception if not found
            $engineerPayout = EngineerPayout::findOrFail($id);

            // Check if ticket work IDs exist and are not empty
            $ticketWorkIds = $engineerPayout->ticket_work_ids;
            if (!is_array($ticketWorkIds) || empty($ticketWorkIds)) {
                // Handle the case where there are no ticket works associated with the EngineerPayout
                return response()->view('backend.errors.404', [], 404);
            }

            // Get ticket works related to the EngineerPayout
            $ticket_works = TicketWork::with('engineer')
                ->whereIn('id', $ticketWorkIds)
                ->get();

            return view('backend.payout.payout-slip', compact('ticket_works'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case where EngineerPayout record is not found
            return response()->view('backend.errors.404', [], 404);
        }
    }
    public function customerPayoutSlip(String $id)
    {
        try {

            // Find the EngineerPayout record by ID or throw an exception if not found
            $customerPayout = CustomerPayout::findOrFail($id);

            // Check if ticket work IDs exist and are not empty
            $ticketWorkIds = $customerPayout->customer_payable_ids;

            // dd($ticketWorkIds);

            if (!is_array($ticketWorkIds) || empty($ticketWorkIds)) {
                // Handle the case where there are no ticket works associated with the EngineerPayout
                return response()->view('backend.errors.404', [], 404);
            }

            // Get ticket works related to the EngineerPayout

            $ticket_works = CustomerPayable::with('engineer')
                ->whereIn('id', $ticketWorkIds)
                ->get();

            // dd($ticket_works);

            return view('backend.customer.customer_payout_slip', compact('ticket_works'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case where EngineerPayout record is not found
            return response()->view('backend.errors.404', [], 404);
        }
    }


    public function updatePayableAmount(Request $request)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'pay_type' => 'required|string', // Ensure pay_type is provided
            'payout_id' => 'required', // Validate payout_id exists in the database
            'amount' => 'required|numeric', // Ensure amount is numeric
        ]);

        // Retrieve the CustomerPayable record based on the payout_id
        $customerPayable = CustomerPayable::findOrFail($request->payout_id);

        // dd($customerPayable);
        // Get the field to update based on pay_type
        $payType = $request->pay_type;
        $amount = $request->amount;

        // Update the corresponding field based on pay_type
        switch ($payType) {
            case 'tool':
                $customerPayable->tool_cost = $amount;
                break;

            case 'travel':
                $customerPayable->travel_cost = $amount;
                break;

            case 'ot':
                $customerPayable->ot_payable = $amount;
                break;

            case 'ooh':
                $customerPayable->ooh_payable = $amount;
                break;

            case 'ww':
                $customerPayable->ww_payable = $amount;
                break;

            case 'hw':
                $customerPayable->hw_payable = $amount;
                break;

            // Add more cases for other pay types if needed
            default:
                return response()->json(['error' => 'Invalid pay type'], 400);
        }

        // Save the updated record
        $customerPayable->save();

        // Return a success response
        return response()->json(['message' => 'Amount updated successfully']);
    }

    public function engineerTicketPayout()
    {
        $engineers = Engineer::get();
        return redirect()->route('payout.show', [$engineers->last()->id, 'filter' => 'all']);
    }


    public function updateEngPayableAmount(Request $request)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'pay_type' => 'required|string', // Ensure pay_type is provided
            'dialy_workId' => 'required', // Validate payout_id exists in the database
            'amount' => 'required|numeric', // Ensure amount is numeric
        ]);

        // Retrieve the CustomerPayable record based on the payout_id
        $dailyWork = TicketWork::findOrFail($request->dialy_workId);

        // dd($dailyWork);
        // Get the field to update based on pay_type
        $payType = $request->pay_type;
        $amount = $request->amount;
        Log::info([
            $request->all()
        ]);

        // Update the corresponding field based on pay_type
        switch ($payType) {
            // case 'tool':
            //     $dailyWork->tool_cost = $amount;
            //     break;

            // case 'travel':
            //     $dailyWork->travel_cost = $amount;
            //     break;

            case 'hourly_payable':
                $dailyWork->hourly_payable = $amount;

                break;

            case 'ot':
                $dailyWork->overtime_payable = $amount;
                if ($amount > 0) {
                    $dailyWork->is_overtime = 1;
                } else {
                    $dailyWork->is_overtime = 0;
                }

                break;

            //  from another model
            case 'ooh':
                $dailyWork->out_of_office_payable = $amount;
                if ($amount > 0) {
                    $dailyWork->is_out_of_office_hours = 1;
                } else {
                    $dailyWork->is_out_of_office_hours = 0;
                }
                break;

            case 'ww':
                $dailyWork->weekend_payable = $amount;
                if ($amount > 0) {
                    $dailyWork->is_weekend = 1;
                } else {
                    $dailyWork->is_weekend = 0;
                }
                break;

            case 'hw':
                $dailyWork->holiday_payable = $amount;
                if ($amount > 0) {
                    $dailyWork->is_holiday = 1;
                } else {
                    $dailyWork->is_holiday = 0;
                }
                break;

            // Add more cases for other pay types if needed
            default:
                return response()->json(['error' => 'Invalid pay type'], 400);
        }

        // Save the updated record
        $dailyWork->save();

        // Return a success response
        return response()->json(['message' => 'Amount updated successfully']);
    }

    public function allEngPayout()
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => ""],
            ['name' => 'Engineer Payout', 'url' => "/payout"],
        ];

        $engineers = collect(Engineer::orderBy('id', 'desc')->get())->toArray();
        $currentYear = now()->year;
        $currentMonth = now()->month;
        $year = [
            ['name' => $currentYear, 'value' => $currentYear],
            ['name' => $currentYear + 1, 'value' => $currentYear + 1],
            ['name' => $currentYear + 2, 'value' => $currentYear + 2],
        ];

        // Generate Month Options
        $month = [
            ['name' => 'January', 'value' => 1],
            ['name' => 'February', 'value' => 2],
            ['name' => 'March', 'value' => 3],
            ['name' => 'April', 'value' => 4],
            ['name' => 'May', 'value' => 5],
            ['name' => 'June', 'value' => 6],
            ['name' => 'July', 'value' => 7],
            ['name' => 'August', 'value' => 8],
            ['name' => 'September', 'value' => 9],
            ['name' => 'October', 'value' => 10],
            ['name' => 'November', 'value' => 11],
            ['name' => 'December', 'value' => 12],
        ];

        $engineers = Engineer::with('enggCharge')->orderBy('id', 'desc')->get();

        return view('backend.payout.all_eng_payout', [
            'breadcrumbs' => $breadcrumbs,
            'engineers' => $engineers,
            'year' => $year,
            'month' => $month,
            'currentYear' => $currentYear,
            'currentMonth' => $currentMonth,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function engineerMonthlyPayouts(Request $request)
    {
        $currencySymbols = [
            'dollar' => '$',
            'euro' => '€',
            'pound' => '£',
            'zloty' => 'zł',
        ];

        $monthlySummary = TicketWork::selectRaw('
            user_id,
            COUNT(DISTINCT ticket_id) as total_tickets,
            COUNT(DISTINCT work_start_date) AS unique_days,
            SUM(TIME_TO_SEC(total_work_time)) as total_work_seconds,
            SUM(hourly_payable) as total_hourly_payable,
            COUNT(hourly_payable) as total_days,
            SUM(overtime_payable) as total_overtime_payable,
            SUM(weekend_payable) as total_weekend_payable,
            SUM(holiday_payable) as total_holiday_payable,
            SUM(out_of_office_payable) as total_out_of_office_payable,
            SUM(travel_cost) as total_travel_cost,
            SUM(tool_cost) as total_tool_cost,
            SUM(other_pay) as total_other_pay,
            SUM(daily_gross_pay) as total_gross_pay,
            SUM(overtime_payable + weekend_payable + holiday_payable + out_of_office_payable + other_pay) as extra_pay_sum,
            SUM(CASE WHEN engineer_payout_status = "paid" THEN daily_gross_pay ELSE 0 END) + SUM(CASE WHEN engineer_payout_status = "paid" THEN other_pay ELSE 0 END) as total_paid,
            SUM(CASE WHEN engineer_payout_status = "pending" THEN daily_gross_pay ELSE 0 END) as total_unpaid
        ')
            ->whereYear('work_start_date', $request->year)
            ->whereMonth('work_start_date', $request->month)
            ->whereNotNull('work_end_date')
            ->whereNotNull('end_time')
            ->groupBy('user_id')
            ->with('engineer', 'engineer.enggCharge')
            ->get();

        $monthlySummary->transform(function ($item) {
            $hours = floor($item->total_work_seconds / 3600);
            $minutes = floor(($item->total_work_seconds % 3600) / 60);
            $item->total_work_time = sprintf('%02d:%02d', $hours, $minutes);
            return $item;
        });

        Log::info([
            $monthlySummary
        ]);

        $selected_month = $request->month;

        $html = view(
            'backend.payout.components.payout_table',
            compact(
                'monthlySummary',
                'currencySymbols',
                'selected_month'
            )
        )->render();

        return response()->json(['html' => $html]);
    }

    public function ticketDetails(Request $request)
    {
        // Convert ticketIds from string to an array
        $ticketWorkIds = array_map('intval', explode(',', $request->query('ticketWorkIds')));

        // Fetch ticket details
        $ticketWorks = TicketWork::whereIn('id', $ticketWorkIds)->with('ticket')->get();

        // If no ticketWorks found, return an empty response
        if ($ticketWorks->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No ticketWorks found for the given IDs.',
                'ticketWorks' => []
            ], 404);
        }

        // Convert data to UTF-8 to prevent encoding issues


        $data = $ticketWorks->toArray();

        return response()->json([
            'success' => true,
            'ticketWorks' => $this->utf8ize($data)
        ], 200);
    }

    private function utf8ize($mixed)
    {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = $this->utf8ize($value);
            }
        } elseif (is_object($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed->$key = $this->utf8ize($value);
            }
        } elseif (is_string($mixed)) {
            return mb_convert_encoding($mixed, 'UTF-8', 'UTF-8');
        }
        return $mixed;
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
                'ticket_breaks'
            )
        )->render();

        return response()->json(['html' => $html]);
    }

    public function saveZusPit(Request $request)
    {
        $request->validate([
            'type' => 'required|in:zus,pit',
            'value' => 'required|numeric',
            'engineer_id' => 'required|exists:engineer_payout,engineer_id',
        ]);

        $type = strtoupper($request->input('type')); // ZUS or PIT
        $value = $request->input('value');

        // Get the payout record for this engineer
        $payout = EngineerPayout::where('engineer_id', $request->engineer_id)->first();

        if (!$payout) {
            return response()->json(['message' => 'Payout record not found.'], 404);
        }

        $payout->$type = $value;
        $payout->save();

        return response()->json(['message' => "$type updated successfully."]);
    }
}
