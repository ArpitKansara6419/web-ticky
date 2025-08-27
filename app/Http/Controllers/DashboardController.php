<?php

namespace App\Http\Controllers;

use App\Enums\EngineerResponseEnum;
use App\Enums\TaskTypeEnum;
use App\Models\Customer;
use App\Models\CustomerPayout;
use App\Models\Engineer;
use App\Models\EngineerLeave;
use App\Models\EngineerPayout;
use App\Models\Holiday;
use App\Models\Lead;
use App\Models\Ticket;
use App\Models\TicketWork;
use App\Models\CustomerPayable;
use App\Models\EngineerNotification;
use App\Models\Notification;
use App\Models\TaskReminder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use function App\Helpers\formatHumanNumber;
use function App\Helpers\getBookings;
use function App\Helpers\getLeads;

class DashboardController extends Controller
{
    public function getDashboard()
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        $currencySymbols = [
            'dollar' => '$', // US Dollar
            'euro' => '€', // Euro
            'pound' => '£', // British Pound
            'zloty' => 'zł'
        ];

        // Fetch the sum of total_payable + other_pay grouped by currency
        $monthlyCustomerPayable = CustomerPayable::select(
            'currency',

            DB::raw('COALESCE(SUM(client_payable), 0) + COALESCE(SUM(tool_cost), 0) + COALESCE(SUM(travel_cost), 0) as total_payable')

        )
            ->whereBetween('work_start_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            // ->whereBetween('work_end_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->groupBy('currency')
            ->get()
            ->keyBy('currency')
            ->toArray();

        // Ensure all currencies are present in the final result with zero values if missing
        $finalMonthlyCustomerPayable = [];
        foreach ($currencySymbols as $currency => $symbol) {
            $finalMonthlyCustomerPayable[] = [
                'currency'       => $currency,
                'total_payable'  => $monthlyCustomerPayable[$currency]['total_payable'] ?? 0,
                'symbol'         => $symbol
            ];
        }

        // dd($finalMonthlyCustomerPayable);


        // Fetch sum of daily_gross_pay + other_pay grouped by currency
        $monthlyEngineerGrossPay = TicketWork::select(
            'currency',
            DB::raw('COALESCE(SUM(daily_gross_pay + other_pay), 0) as total_amount')
        )
            ->whereMonth('work_start_date', Carbon::now()->month)
            ->whereYear('work_start_date', Carbon::now()->year)
            ->groupBy('currency')
            ->get()
            ->keyBy('currency')
            ->toArray();

        // Ensure all currencies exist in the final result
        // $finalEngineerGrossPay = [];
        // foreach ($currencySymbols as $currency => $symbol) {
        //     $finalEngineerGrossPay[] = [
        //         'currency'      => $currency,
        //         'total_amount'  => $monthlyEngineerGrossPay[$currency]['total_amount'] ?? 0,
        //         'symbol'        => $symbol
        //     ];
        // }

        $workedEngineerIds = TicketWork::whereMonth('work_start_date', Carbon::now()->month)
            ->whereYear('work_start_date', Carbon::now()->year)
            ->pluck('user_id')
            ->unique();

        $fullTimeEngineers = Engineer::whereIn('id', $workedEngineerIds)
            ->where('job_type', 'full_time')
            ->with('enggCharge')
            ->get();

        // 3. Add their monthly charge to the correct currency
        foreach ($fullTimeEngineers as $engineer) {
            $charge = $engineer->enggCharge->monthly_charge ?? 0;
            $currency = $engineer->enggCharge->currency_type ?? null;

            if ($currency) {
                if (!isset($monthlyEngineerGrossPay[$currency])) {
                    $monthlyEngineerGrossPay[$currency] = ['total_amount' => 0];
                }
                $monthlyEngineerGrossPay[$currency]['total_amount'] += $charge;
            }
        }

        // 4. Create final associative array keyed by currency
        $finalEngineerGrossPay = [];

        foreach ($currencySymbols as $currency => $symbol) {
            $finalEngineerGrossPay[$currency] = [
                'currency'      => $currency,
                'total_amount'  => $monthlyEngineerGrossPay[$currency]['total_amount'] ?? 0,
                'symbol'        => $symbol
            ];
        }

        $customerPayouts = CustomerPayout::selectRaw('currency, SUM(total_payable) as total_amount')
            ->whereBetween('created_at', [$startDate, $endDate]) // Filter by current month
            ->groupBy('currency')
            ->get();

        $leaveApproved = EngineerLeave::where([
            'leave_approve_status' => 'approved',
        ])
        ->whereMonth('paid_from_date', Carbon::now()->format('M'))->sum('paid_leave_days');


        $insights = [
            'leads' => Lead::whereBetween('created_at', [$startDate, $endDate])->count(),
            'engineers'  => Engineer::count(),
            'customers'   => Customer::count(),
            'holiday' => Holiday::whereMonth('date', now()->month)->count(),
            'tickets' => Ticket::whereBetween('task_start_date', [$startDate, $endDate])->count(),
        ];


        

        $allCount = Notification::latest()->count();
        $unreadCount = Notification::where('is_read', false)->latest()->count();
        $readCount = Notification::where('is_read', true)->latest()->count();

        $requestList = EngineerLeave::with('engineer')->whereHas('engineer')->where('leave_approve_status', 'pending')->get();

        return view('backend.dashboard', [
            'insights'  =>  $insights,
            'finalMonthlyCustomerPayable' => $finalMonthlyCustomerPayable,
            'finalEngineerGrossPay' => $finalEngineerGrossPay,
            'customerPayouts' => $customerPayouts,
            'currencySymbols' => $currencySymbols,
            'requestList' => $requestList,
            'leaveApproved' => $leaveApproved,
            'allCount' => $allCount,
            'unreadCount' => $unreadCount,
            'readCount' => $readCount,

        ]);
    }

    public function filterDashboardStastics(Request $request)
    {
        // Leaves Approved
        $paidleaveApproved = EngineerLeave::where([
            'leave_approve_status' => 'approved',
        ])->where(function ($q) use ($request) {
            $q->whereRaw("DATE_FORMAT(paid_from_date, '%Y-%m') = ?", [$request->filter_date])
                ->orwhereRaw("DATE_FORMAT(paid_to_date, '%Y-%m') = ?", [$request->filter_date]);
        })
        ->sum('paid_leave_days');

        $unpaidleaveApproved = EngineerLeave::where([
            'leave_approve_status' => 'approved',
        ])->where(function ($q) use ($request) {
            $q->whereRaw("DATE_FORMAT(unpaid_from_date, '%Y-%m') = ?", [$request->filter_date])
                ->orwhereRaw("DATE_FORMAT(unpaid_to_date, '%Y-%m') = ?", [$request->filter_date]);
        })
        ->sum('unpaid_leave_days');

        $leaveApproved = $paidleaveApproved + $unpaidleaveApproved;
        // Tickets
        $tickets = Ticket::whereRaw("DATE_FORMAT(task_start_date, '%Y-%m') = ?", [$request->filter_date])
            ->count();

        // Leads
        $leads = Lead::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$request->filter_date])->count();

        // Engineer Payouts
        $currencySymbols = [
            'dollar' => '$', // US Dollar
            'euro' => '€', // Euro
            'pound' => '£', // British Pound
            'zloty' => 'zł'
        ];
        $monthlyEngineerGrossPay = TicketWork::select(
            'currency',
            DB::raw('COALESCE(SUM(daily_gross_pay + other_pay), 0) as total_amount')
        )
            // ->whereMonth('work_start_date', Carbon::now()->month)
            // ->whereYear('work_start_date', Carbon::now()->year)
            ->whereRaw("DATE_FORMAT(work_start_date, '%Y-%m') = ?", [$request->filter_date])
            ->where('engineer_payout_status', 'pending')
            ->groupBy('currency')
            ->get()
            ->keyBy('currency')
            ->toArray();

        $workedEngineerIds = TicketWork::
            whereRaw("DATE_FORMAT(work_start_date, '%Y-%m') = ?", [$request->filter_date])
            // whereMonth('work_start_date', Carbon::now()->month)
            // ->whereYear('work_start_date', Carbon::now()->year)
            ->pluck('user_id')
            ->unique();

        $fullTimeEngineers = Engineer::whereIn('id', $workedEngineerIds)
            ->where('job_type', 'full_time')
            ->with('enggCharge')
            ->get();

        // 3. Add their monthly charge to the correct currency
        foreach ($fullTimeEngineers as $engineer) {
            $charge = $engineer->enggCharge->monthly_charge ?? 0;
            $currency = $engineer->enggCharge->currency_type ?? null;

            if ($currency) {
                if (!isset($monthlyEngineerGrossPay[$currency])) {
                    $monthlyEngineerGrossPay[$currency] = ['total_amount' => 0];
                }
                $monthlyEngineerGrossPay[$currency]['total_amount'] += $charge;
            }
        }

        // 4. Create final associative array keyed by currency
        $finalEngineerGrossPay = [];

        foreach ($currencySymbols as $currency => $symbol) {
            $finalEngineerGrossPay[$currency] = [
                'currency'      => $currency,
                'total_amount'  => $monthlyEngineerGrossPay[$currency]['total_amount'] ?? 0,
                'symbol'        => $symbol
            ];
        }

        $engineer_payouts_html = view('backend.dash.engineer_payouts', compact('finalEngineerGrossPay'))->render();


        // Customers PAyable
        $monthlyCustomerPayable = CustomerPayable::select(
            'currency',

            DB::raw('COALESCE(SUM(client_payable), 0) + COALESCE(SUM(tool_cost), 0) + COALESCE(SUM(travel_cost), 0) as total_payable')

        )
            ->whereRaw("DATE_FORMAT(work_start_date, '%Y-%m') = ?", [$request->filter_date])
            ->groupBy('currency')
            ->get()
            ->keyBy('currency')
            ->toArray();

        // Ensure all currencies are present in the final result with zero values if missing
        $finalMonthlyCustomerPayable = [];
        foreach ($currencySymbols as $currency => $symbol) {
            $finalMonthlyCustomerPayable[] = [
                'currency'       => $currency,
                'total_payable'  => $monthlyCustomerPayable[$currency]['total_payable'] ?? 0,
                'symbol'         => $symbol
            ];
        }

        $customer_receivable_html = view('backend.dash.customer_payouts', compact('finalMonthlyCustomerPayable'))->render();


        return response()->json([
            'leaveApproved' => (int)$leaveApproved,
            'tickets' => (int)$tickets,
            'leads' => (int)$leads,
            'engineer_payouts_html' => $engineer_payouts_html,
            'customer_receivable_html' => $customer_receivable_html
        ]);
    }

    // public function getEvents()
    // {
    //     $tickets = Ticket::all()->map(function ($ticket) {
    //         return [
    //             'id' => $ticket->id,
    //             'title' => '#' . $ticket->ticket_code . ' | ' . $ticket->task_time ,
    //             'date' => $ticket->task_start_date, // Rename 'start_date' to 'date'
    //             'end_date' => $ticket->task_end_date,
    //             'start' => $ticket->task_start_date.'T'.$ticket->task_time,
    //         ];
    //     });
    //     return response()->json($tickets);
    // }

    public function getEvents(Request $request)
    {
        $date = $request->query('date'); // Get the selected date from request

        // Convert date to Carbon instance
        $carbonDate = Carbon::parse($date);

        // Check if the date falls on a weekend (Saturday = 6, Sunday = 7)
        if ($carbonDate->isWeekend()) {
            return response()->json([]); // Return empty response if it's Saturday or Sunday
        }


        $tickets = Ticket::when($date, function ($query) use ($date) {
            return $query->whereDate('task_start_date', '<=', $date)
                ->whereDate('task_end_date', '>=', $date);
        })
            ->where(function ($query) {
                $query->where('status', '!=', 'close')
                    ->orWhereNull('status')
                    ->orWhere(function ($q) {
                        $q->where('status', 'close')
                            ->whereDate('task_end_date', '>=', now()->toDateString());
                    });
            })
            ->get()
            ->map(function ($ticket) use ($date) {
                // Get the work log for the selected date
                $ticketWork = $ticket->ticketWork()
                    ->whereDate('work_start_date', $date)
                    ->where('user_id', $ticket->engineer_id)
                    ->first();

                $isLate = false;

                if ($ticketWork && $ticketWork->start_time) {
                    $taskStartTime = Carbon::parse($ticket->task_time);
                    $actualStartTime = Carbon::parse($ticketWork->start_time);

                    $today = Carbon::today();
                    $taskDate = Carbon::parse($date);

                    if ($taskDate->lessThanOrEqualTo($today)) {
                        $isLate = $actualStartTime->greaterThan($taskStartTime);
                    }
                }
                $start = Carbon::parse($ticket->task_start_date);
                $end = Carbon::parse($ticket->task_end_date);

                $dateCollection = collect();

                while ($start->lte($end)) {
                    $start_date = $start->format('Y-m-d');
                    $holiday = Holiday::where('date', $start_date)->count();

                    if($holiday == 0)
                    {
                        $dateCollection->push($start->format('Y-m-d'));
                    }
                    
                    $start->addDay();
                }
                $uniqueDates = $dateCollection->unique()->values()->all(); // ✅ this is your final array of unique dates

                $notifications = TaskReminder::where('ticket_id', $ticket->id)
                        ->where('engineer_id', $ticket->engineer_id)
                        ->whereDate('reminder_at', $date)
                        ->orderBy('id', 'DESC')
                        ->get();
                
                $notifications_count = $notifications->count();
               
                $prepare_for_display = '';

                if($notifications_count > 0)
                {
                    $prepare_for_display .= $notifications_count;

                    $last = $notifications[$notifications_count-1];

                    if(!$last->user_response){
                        $prepare_for_display .= " - No Response";
                    }else if($last->user_response === EngineerResponseEnum::YES->value){
                        $prepare_for_display .= " - Yes";
                    }else if($last->user_response === EngineerResponseEnum::NO->value){
                        $prepare_for_display .= " - No : ".$last->reason;
                    }
                }

                return [
                    'id' => $ticket->id,
                    // 'title' => '#' . $ticket->ticket_code . ' | ' . $ticket->task_time,
                    'ticketId' => '#' . $ticket->ticket_code,
                    'task' => $ticket->task_name,
                    'address' => $ticket->apartment . ',' . $ticket->add_line_1 . ',' . $ticket->add_line_2 . ',' . $ticket->country . ',' . $ticket->city . ',' . $ticket->zipcode,
                    // 'date' => Carbon::parse($ticket->task_start_date)->format('M d, Y'), // Format: Mar 11, 2025
                    // 'end_date' => Carbon::parse($ticket->task_end_date)->format('M d, Y'), // Format: Mar 11, 2025
                    // 'time' => Carbon::parse($ticket->task_time)->format('H:i'), // Format: 06:00
                    'date' => Carbon::parse($ticket->ticket_start_date_tz)->format('M d, Y'),
                    'end_date' => Carbon::parse($ticket->ticket_start_date_tz)->format('M d, Y'),
                    'time' => Carbon::parse($ticket->ticket_time_tz)->format('H:i'),
                    'status' => $ticket->status,
                    'isLate' => $isLate,
                    "eng_status" =>  $ticket->engineer_status,
                    'uniqueDates' => $uniqueDates,
                    "today" => $date,
                    "prepare_for_display_notification" => $prepare_for_display,
                    'notifications_count' => $notifications_count,
                    'ticket'=>$ticket
                ];
            });

        return response()->json($tickets);
    }


    public function getTicketChartData(Request $request)
    {
        // Validate the incoming request for time period
        $validated = $request->validate([
            'time_period' => 'required|string|in:yesterday,today,last7days,last30days,last90days',
        ]);

        // Get data for the selected time period
        $data = $this->getDataForTimePeriod($validated['time_period']);

        // Return the data as a JSON response
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    private function getDataForTimePeriod($timePeriod)
    {
        switch ($timePeriod) {
            case 'yesterday':
                // Get data for yesterday
                $data = Ticket::whereDate('created_at', now()->subDay()->toDateString())
                    ->selectRaw('DATE(created_at) as date, count(*) as tickets')
                    ->groupBy('date')
                    ->get();
                break;

            case 'today':
                // Get data for today
                $data = Ticket::whereDate('created_at', now()->toDateString())
                    ->selectRaw('DATE(created_at) as date, count(*) as tickets')
                    ->groupBy('date')
                    ->get();
                break;

            case 'last7days':
                // Get data for the last 7 days
                $data = Ticket::whereBetween('created_at', [now()->subDays(7), now()])
                    ->selectRaw('DATE(created_at) as date, count(*) as tickets')
                    ->groupBy('date')
                    ->get();
                break;

            case 'last30days':
                // Get data for the last 30 days
                $data = Ticket::whereBetween('created_at', [now()->subDays(30), now()])
                    ->selectRaw('DATE(created_at) as date, count(*) as tickets')
                    ->groupBy('date')
                    ->get();
                break;

            case 'last90days':
                // Get data for the last 90 days
                $data = Ticket::whereBetween('created_at', [now()->subDays(90), now()])
                    ->selectRaw('DATE(created_at) as date, count(*) as tickets')
                    ->groupBy('date')
                    ->get();
                break;

            default:
                $data = [];
                break;
        }

        // Format data for the chart (e.g., x-values for dates and y-values for ticket counts)
        $xVal = $data->pluck('date')->toArray();
        $yTickets = $data->pluck('tickets')->toArray();

        return [
            'xVal' => $xVal,
            'yTickets' => $yTickets
        ];
    }

    public function getMonthlyTickets(Request $request)
    {

        $year = $request->query('year');
        $month = $request->query('month');

        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $monthData = [];

        while ($startOfMonth <= $endOfMonth) {

            $date = $startOfMonth->toDateString(); // Format: YYYY-MM-DD

            // Fetch tickets that are active on this date
            $tickets = Ticket::whereDate('task_start_date', '<=', $date)
                ->whereDate('task_end_date', '>=', $date)
                ->select('id', 'task_start_date as start', 'task_end_date as end')
                ->get()
                ->toArray();

            if (count($tickets) > 0) {
                $monthData[] = [
                    'start' => $date,
                    'tickets' => $tickets
                ];
            }

            $startOfMonth->addDay(); // Move to the next day
        }

        return response()->json($monthData);
    }

    public function notificationLazyLoad(Request $request)
    {
        $notifications = Notification::query();

        if($request->is_read === true || $request->is_read === false)
        {
            $notifications->where('is_read', $request->is_read);
        }

        $notifications->orderBy('id', 'DESC');
        
        $allNotifications = $notifications->paginate(15, ['*'], 'page', $request->page);
        
        $html = view('dashboard.notification_lazy_load', compact('allNotifications'))->render();

        return response()->json([
            'html' => $html,
            'notifications' => $allNotifications
        ]);
    }
}
