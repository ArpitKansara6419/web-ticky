<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Customer;
use App\Models\CustomerPayout;
use App\Models\Engineer;
use App\Models\TicketWork;
use App\Models\EngineerPayout;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerPayable;
use App\Models\DailyWorkNote;
use App\Models\EngineerCharge;
use App\Models\EngineerDailyWorkExpense;
use App\Models\EngineerExtraPay;
use App\Models\Lead;
use App\Models\WorkBreak;
use Illuminate\Support\Facades\Log;

class CustomerPayoutController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => "/all-customer-payout"],
            ['name' => 'Customer Receivable', 'url' => "/all-customer-payout"],
            ['name' => 'Customer Invoice', 'url' => "/"],
        ];
        $payouts = CustomerPayout::where('customer_id', $request->customer)->with('customer')->orderBy('id', 'desc')->get();
        $customer = Customer::find($request->customer);

        return view('backend.customer.index-payout', [
            'breadcrumbs' => $breadcrumbs,
            'payouts' => $payouts,
            'customer' => $customer
        ]);
    }

    public function show($id)
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => "/all-customer-payout"],
            ['name' => 'Customer Receivable', 'url' => "/all-customer-payout"],
            ['name' => 'Customer Invoices', 'url' => "/show"],
        ];
        $customer = Customer::findOrFail($id);
        $customers = Customer::get();
        // need to find ticket where 
        $ticketIds = Ticket::where('customer_id', $id)->pluck('id');
        $dailyWorks = TicketWork::with('engineer', 'ticket')->WhereIn('ticket_id', $ticketIds)->get();

        $filter = request()->get('filter'); // Get filter value from URL
        $currency = request()->get('currency'); // Get currency filter
        $yearFilter = request()->get('year'); // Get year filter
        $monthFilter = request()->get('month'); // Get month filter

        $customerInvoice = CustomerPayable::with(['engineer', 'customer', 'ticket', 'ticketWork', 'lead'])
            ->where('customer_id', $id)
            ->when($filter === 'compeleted', function ($query) {
                return $query->where('payment_status', 'completed'); // Show only paid records
            })
            ->when($filter === 'processing', function ($query) {
                return $query->where('payment_status', 'processing'); // Show only processing records
            })
            ->when($filter === 'pending', function ($query) {
                return $query->where(function ($query) {
                    $query->whereNull('payment_status') // Show records where status is NULL
                        ->orWhere('payment_status', 'pending'); // or status is pending
                });
            })
            ->when($currency && $currency != 'all', function ($query) use ($currency) {
                return $query->where('currency', $currency);
            })
            ->when($yearFilter, function ($query, $yearFilter) {
                return $query->whereYear('work_start_date', $yearFilter);
            })
            ->when($monthFilter, function ($query, $monthFilter) {
                return $query->whereMonth('work_start_date', $monthFilter);
            })
            ->orderBy('id', 'desc')
            ->get();

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

        $currencySymbols = [
            'dollar' => '$',
            'euro' => '€',
            'pound' => '£',
            'zloty' => 'zł',
        ];

        $selectedCustomerId = $id;

        $banks = Bank::activeBank()->get();

        $last_paid_bank_id = CustomerPayout::where('customer_id', $selectedCustomerId)->latest()->first();
        if($last_paid_bank_id)
        {
            $last_paid_bank_id = $last_paid_bank_id->bank_id;
        }

        return view('backend.customer.customer_invoice', [
            'breadcrumbs' => $breadcrumbs,
            'customer' => $customer,
            'daily_works' => $dailyWorks,
            'customer_invoice' => $customerInvoice,
            'customers' => $customers,
            'selectedCustomerId' => $selectedCustomerId,
            'year' => $year,
            'month' => $month,
            'currentYear' => $currentYear,
            'selectedYear' => $yearFilter ?? $currentYear,
            'currentMonth' => $currentMonth,
            'selectedMonth' => $monthFilter ?? $currentMonth,
            'banks' => $banks,
            'last_paid_bank_id' => $last_paid_bank_id,
        ]);
    }

    public function store(Request $request)
    {

        $requestData = $request->all();
        // dd($requestData);

        DB::beginTransaction();

        if (isset($requestData['customer_payable_ids']) &&  !empty($requestData['customer_payable_ids'])) {
            foreach ($requestData['customer_payable_ids'] as $key => $value) {
                CustomerPayable::where([
                    'id' => $value
                ])->update([
                    'payment_status' => 'processing'
                ]);
            }
        }

        $bank = Bank::find($requestData['bank_id'])->toArray();

        CustomerPayout::create([
            'customer_id' => $requestData['customer_id'],
            'customer_payable_ids' => $requestData['customer_payable_ids'] ?? json_encode([]),
            'total_payable' => $requestData['total_payable'],
            'extra_incentive' => 0,
            'gross_pay' => $requestData['total_payable'],
            'currency' => $requestData['payable_currency'],
            'note' => 'NA',
            'payment_date' => now()->addDays(30)->format('Y-m-d'),
            'payment_status' => 'processing',
            'payment_type' => 'bank_transfer',
            'bank_id' => $requestData['bank_id'],
            'bank_details' => $bank,
        ]);

        session()->flash('toast', [
            'type'    => 'success',
            'message' => 'Customer invoice created successfully.'
        ]);

        DB::commit();

        // return redirect()->route('customer-invoice.index');
        return redirect()->route('customer-invoice.index', ['customer' => $requestData['customer_id'], 'filter' => 'all']);
    }

    public function destroy(string $id)
    {
        // Find the CustomerPayout record
        $customerPayout = CustomerPayout::findOrFail($id);

        // Ensure customer_payable_ids is an array
        $customerPayableIds = is_array($customerPayout->customer_payable_ids)
            ? $customerPayout->customer_payable_ids
            : json_decode($customerPayout->customer_payable_ids, true);

        if (!empty($customerPayableIds)) {
            // Update 'payment_status' to 'pending' for related CustomerPayable records
            CustomerPayable::whereIn('id', $customerPayableIds)->update(['payment_status' => 'pending']);
        }

        // Delete the CustomerPayout record
        $customerPayout->delete();
        // Flash success message
        session()->flash('toast', [
            'type'    => 'success',
            'message' => 'Payout Deleted Successfully.'
        ]);

        return redirect()->back()->with('success', 'Payout Deleted successfully.');
    }


    // public function dailyPayoutUpdate(Request $request)
    // {
    //     // dd($request->all());

    //     $request->validate([
    //         'ticket_work_id' => 'required|exists:ticket_daily_work,id', // Ensure the ID exists in the table
    //         'travel_cost' => 'nullable|numeric',
    //         'tool_cost' => 'nullable|numeric',
    //         'daily_total_payable' => 'nullable|numeric',
    //         'overtime' => 'nullable|string',
    //     ]);

    //     // Fetch the existing record
    //     $ticketWork = TicketWork::find($request->ticket_work_id);

    //     // Update only the fields provided in the request, keeping others unchanged
    //     $ticketWork->update([
    //         'travel_cost' => $request->travel_cost ?? $ticketWork->travel_cost,
    //         'tool_cost' => $request->tool_cost ?? $ticketWork->tool_cost,
    //         'hourly_payable' => $request->daily_total_payable ?? $ticketWork->daily_total_payable,
    //         'overtime_hour' => $request->overtime ?? $ticketWork->overtime,
    //     ]);

    //     // Return success response or redirect
    //     return redirect()->back()->with('success', 'Daily payout updated successfully!');
    // }

    // public function getTicketData($id)
    // {
    //     $daily_work_data = TicketWork::selectRaw('id, user_id, user_id, work_start_date, total_work_time, hourly_payable, overtime_hour, travel_cost, tool_cost, hourly_payable, is_out_of_office_hours, is_weekend, is_holiday')
    //         ->where('id', $id)
    //         ->first();
    //     return response()->json([
    //         'status' => 'success',
    //         'daily_work_data' => $daily_work_data,
    //     ], 200);
    // }

    // public function payoutSlip(String $id)
    // {

    //     $engineerPayout = EngineerPayout::find($id); // Find the record by ID
    //     $ticketWorkIds = $engineerPayout->ticket_work_ids; // Directly access the casted attribute


    //     $ticket_works = TicketWork::with('engineer')->WhereIn('id', $ticketWorkIds)->get();
    //     // dd($ticket_works);


    //     return view(
    //         'backend/payout/payout-slip',
    //         [
    //             'ticket_works' => $ticket_works,
    //             // 'engineer' => $engineer,
    //         ]
    //     );
    // }


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

    public function customerInvoiceRedirection()
    {
        $customers = Customer::get();
        return redirect()->route('customer-invoice.show', [$customers->last()->id, 'filter' => 'all']);
    }

    public function allCustomerPayout()
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => ""],
            ['name' => 'Customer Receivable', 'url' => "/payout"],
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

        $currencySymbols = [
            'dollar' => '$',
            'euro' => '€',
            'pound' => '£',
            'zloty' => 'zł',
        ];

        $monthlySummary = CustomerPayable::selectRaw(
            '
            customer_id,
            COUNT(DISTINCT ticket_id) as total_tickets,
            COUNT(DISTINCT work_start_date) AS unique_days,
            SEC_TO_TIME(SUM(TIME_TO_SEC(total_work_time))) as total_work_time, 
            SUM(hourly_payable) as total_hourly_payable,
            COUNT(hourly_payable) as total_days,
            SUM(ot_payable) as total_overtime_payable,
            SUM(ww_payable) as total_weekend_payable,
            SUM(hw_payable) as total_holiday_payable,
            SUM(ooh_payable) as total_out_of_office_payable,
            SUM(client_payable) as total_gross_pay,
            SUM(CASE WHEN currency = "dollar" THEN travel_cost ELSE 0 END) as travel_dollar,
            SUM(CASE WHEN currency = "euro" THEN travel_cost ELSE 0 END) as travel_euro,
            SUM(CASE WHEN currency = "zloty" THEN travel_cost ELSE 0 END) as travel_zloty,
            SUM(CASE WHEN currency = "pound" THEN travel_cost ELSE 0 END) as travel_pound,
            SUM(CASE WHEN currency = "dollar" THEN tool_cost ELSE 0 END) as tool_dollar,
            SUM(CASE WHEN currency = "euro" THEN tool_cost ELSE 0 END) as tool_euro,
            SUM(CASE WHEN currency = "zloty" THEN tool_cost ELSE 0 END) as tool_zloty,
            SUM(CASE WHEN currency = "pound" THEN tool_cost ELSE 0 END) as tool_pound,
           
            SUM(CASE WHEN currency = "dollar" THEN client_payable ELSE 0 END) + SUM(CASE WHEN currency = "dollar" THEN travel_cost ELSE 0 END) + SUM(CASE WHEN currency = "dollar" THEN tool_cost ELSE 0 END) as total_dollar,


            SUM(CASE WHEN currency = "euro" THEN client_payable ELSE 0 END) + SUM(CASE WHEN currency = "euro" THEN travel_cost ELSE 0 END) + SUM(CASE WHEN currency = "euro" THEN tool_cost ELSE 0 END) as total_euro ,


            SUM(CASE WHEN currency = "zloty" THEN client_payable ELSE 0 END) + SUM(CASE WHEN currency = "zloty" THEN travel_cost ELSE 0 END) + SUM(CASE WHEN currency = "zloty" THEN tool_cost ELSE 0 END) as total_zloty,

            SUM(CASE WHEN currency = "pound" THEN client_payable ELSE 0 END) + SUM(CASE WHEN currency = "pound" THEN travel_cost ELSE 0 END) + SUM(CASE WHEN currency = "pound" THEN tool_cost ELSE 0 END) as total_pound,


            SUM(CASE WHEN currency = "dollar" AND payment_status = "completed" THEN client_payable ELSE 0 END)  + SUM(CASE WHEN currency = "dollar" AND payment_status = "completed" THEN  travel_cost ELSE 0 END) + SUM(CASE WHEN currency = "dollar" AND payment_status = "completed"  THEN tool_cost ELSE 0 END) as paid_dollar,


            SUM(CASE WHEN currency = "euro" AND payment_status = "completed" THEN client_payable ELSE 0 END) + SUM(CASE WHEN currency = "euro" AND payment_status = "completed" THEN  travel_cost ELSE 0 END) + SUM(CASE WHEN currency = "euro" AND payment_status = "completed"  THEN tool_cost ELSE 0 END) as paid_euro,


            SUM(CASE WHEN currency = "zloty" AND payment_status = "completed" THEN client_payable ELSE 0 END) + SUM(CASE WHEN currency = "zloty" AND payment_status = "completed" THEN  travel_cost ELSE 0 END) + SUM(CASE WHEN currency = "zloty" AND payment_status = "completed"  THEN tool_cost ELSE 0 END) as paid_zloty,


            SUM(CASE WHEN currency = "pound" AND payment_status = "completed" THEN client_payable ELSE 0 END) + SUM(CASE WHEN currency = "pound" AND payment_status = "completed" THEN  travel_cost ELSE 0 END) + SUM(CASE WHEN currency = "pound" AND payment_status = "paid"  THEN tool_cost ELSE 0 END) as paid_pound'

        )
            ->whereYear('work_start_date', 2025)
            ->whereMonth('work_start_date', 03)
            ->groupBy('customer_id')
            ->with('customer', 'engineer.enggCharge')
            ->get();


        $engineers = Engineer::with('enggCharge')->orderBy('id', 'desc')->get();

        return view('backend.customer.all_customer_payout', [
            'breadcrumbs' => $breadcrumbs,
            'engineers' => $engineers,
            'year' => $year,
            'month' => $month,
            'currentYear' => $currentYear,
            'currentMonth' => $currentMonth,
            'monthlySummary' => $monthlySummary,
            'currencySymbols' => $currencySymbols
        ]);
    }


    public function customerPayoutFilter(Request $request)
    {


        // Filter
        $year = $request->year;
        $month = $request->month;
        // $search = $request->search;

        $monthlySummary = CustomerPayable::selectRaw(
            '
            customer_id,
            COUNT(DISTINCT ticket_id) as total_tickets,
            COUNT(DISTINCT work_start_date) AS unique_days,
            SEC_TO_TIME(SUM(TIME_TO_SEC(total_work_time))) as total_work_time, 
            SUM(hourly_payable) as total_hourly_payable,
            COUNT(hourly_payable) as total_days,
            SUM(ot_payable) as total_overtime_payable,
            SUM(ww_payable) as total_weekend_payable,
            SUM(hw_payable) as total_holiday_payable,
            SUM(ooh_payable) as total_out_of_office_payable,
            SUM(client_payable) as total_gross_pay,
            SUM(CASE WHEN currency = "dollar" THEN travel_cost ELSE 0 END) as travel_dollar,
            SUM(CASE WHEN currency = "euro" THEN travel_cost ELSE 0 END) as travel_euro,
            SUM(CASE WHEN currency = "zloty" THEN travel_cost ELSE 0 END) as travel_zloty,
            SUM(CASE WHEN currency = "pound" THEN travel_cost ELSE 0 END) as travel_pound,
            SUM(CASE WHEN currency = "dollar" THEN tool_cost ELSE 0 END) as tool_dollar,
            SUM(CASE WHEN currency = "euro" THEN tool_cost ELSE 0 END) as tool_euro,
            SUM(CASE WHEN currency = "zloty" THEN tool_cost ELSE 0 END) as tool_zloty,
            SUM(CASE WHEN currency = "pound" THEN tool_cost ELSE 0 END) as tool_pound,


            SUM(CASE WHEN currency = "dollar" THEN client_payable + COALESCE(tool_cost, 0) + COALESCE(travel_cost, 0) ELSE 0 END) AS total_dollar,
            SUM(CASE WHEN currency = "euro" THEN   client_payable + COALESCE(tool_cost, 0) + COALESCE(travel_cost, 0) ELSE 0 END) AS total_euro,
            SUM(CASE WHEN currency = "zloty" THEN  client_payable + COALESCE(tool_cost, 0) + COALESCE(travel_cost, 0) ELSE 0 END) AS total_zloty,
            SUM(CASE WHEN currency = "pound" THEN  client_payable + COALESCE(tool_cost, 0) + COALESCE(travel_cost, 0) ELSE 0 END) AS total_pound,

            SUM(CASE WHEN currency = "dollar" AND payment_status = "completed" THEN 
            (client_payable + COALESCE(tool_cost, 0) + COALESCE(travel_cost, 0)) ELSE 0 
            END) AS paid_dollar,

            SUM(CASE WHEN currency = "euro" AND payment_status = "completed" THEN 
            (client_payable + COALESCE(tool_cost, 0) + COALESCE(travel_cost, 0)) ELSE 0 
            END) AS paid_euro,

            SUM(CASE WHEN currency = "zloty" AND payment_status = "completed" THEN 
            (client_payable + COALESCE(tool_cost, 0) + COALESCE(travel_cost, 0)) ELSE 0 
            END) AS paid_zloty,

            SUM(CASE WHEN currency = "pound" AND payment_status = "completed" THEN 
            (client_payable + COALESCE(tool_cost, 0) + COALESCE(travel_cost, 0)) ELSE 0 
            END) AS paid_pound'

        )
            ->when($year, function ($query) use ($year) {
                return $query->whereYear('work_start_date', $year);
            })
            ->when($month, function ($query) use ($month) {
                return $query->whereMonth('work_start_date', $month);
            })
            ->groupBy('customer_id')
            ->with('customer', 'engineer.enggCharge')
            ->get();


        return view('backend.customer_payable.payable_table', [
            'year' => $year,
            'month' => $month,
            'monthlySummary' => $monthlySummary,
        ]);
    }

    public function fetchPopup(Request $request)
    {
        // Get the ticket ID from the request
        $ticketWorkId = $_GET['id'];

        $ticket_works = CustomerPayable::select('*')
            ->with('ticket')->where('ticket_work_id', $ticketWorkId)->first();


        // Fetch the ticket details
        $ticketWorks = CustomerPayable::select('*')
            ->Where('ticket_work_id', $ticketWorkId)->first();

        
        $ticket = Ticket::find($ticketWorks->ticket_id);

        $lead = Lead::where([
            'id' => $ticket->lead_id
        ])->first();


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


        $currencySymbols = [
            'dollar' => '$',
            'euro' => '€',
            'pound' => '£',
            'zloty' => 'zł',
        ];

        // Get the correct currency symbol (default to $ if currency_type is missing)
        $ticket_currency = isset($ticket_works->ticket) ?
            ($currencySymbols[$ticket_works->ticket->currency_type] ?? '') : '';

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

        $engineer_ticket_works = TicketWork::with(['breaks', 'ticket'])->where([
            'id' => $ticketWorkId
        ])->first();

        $ticket_breaks = WorkBreak::where([
            'ticket_work_id' => $ticketWorkId
        ])->get();

        $html = view(
            'backend.customer.components.customer_ticket_detail_popup',
            compact(
                'ticket',
                'lead',
                'ticket_works',
                'ticket_currency',
                'engineerCurrency',
                'engineerExtraPay',
                'engineerRates',
                'otherExpenses',
                'ticketNotesAttachments',
                'ticketWorkId',
                'totalBreakTime',
                'totalWorkingTime',
                'engineer_ticket_works',
                'ticket_breaks',
            )
        )->render();

        return response()->json(['html' => $html]);
    }

    public function invoiceStatusUpdate(Request $request)
    {

        $customerPayable = CustomerPayout::where(['id' => $request->id])->first();
        $customerPayableIds = $customerPayable->customer_payable_ids;

        foreach ($customerPayableIds as $key => $value) {
            CustomerPayable::where([
                'id' => $value
            ])->update([
                'payment_status' => 'completed'
            ]);
        }

        CustomerPayout::where([
            'id' => $request->id
        ])->update([
            'payment_status' => 'completed'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Invoice status updated to paid.'
        ]);
    }
}
