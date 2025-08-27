<?php 

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\HolidayResource;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Engineer;
use App\Models\TicketWork;
use App\Models\EngineerLeave;
use App\Models\WorkBreak;
use App\Models\Ticket;
use App\Models\Holiday;
use App\Http\Resources\TicketResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{

    public function getMonthlyAttendance($id, Request $request)
    {
        $currentDate = now()->format('Y-m-d');
        // Get month and year from request, default to current month
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
    
        // Generate all dates for the requested month
        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();
        $allDates = $startOfMonth->daysInMonth;
        $dates = collect(range(1, $allDates))->map(function ($day) use ($startOfMonth) {
            return $startOfMonth->copy()->setDay($day)->format('Y-m-d');
        });
    
        // Fetch engineer details
        $engineer = Engineer::find($id);
        if (!$engineer) {
            return response()->json(['message' => 'Engineer not found'], 404);
        }
    
        // Get attendance records for the engineer within the current month
        $ticketWorks = TicketWork::where('user_id', $id)
            ->whereBetween('work_start_date', [$startOfMonth, $endOfMonth])
            ->orderBy('work_start_date', 'asc')
            ->get()
            ->keyBy('work_start_date');

        // Fetch leave applications for the engineer with approved status
        $leaveApplications = EngineerLeave::where('engineer_id', $id)
            ->where('leave_approve_status', 'approved')
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('paid_from_date', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('paid_to_date', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('unpaid_from_date', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('unpaid_to_date', [$startOfMonth, $endOfMonth])
                    ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                        $q->where('paid_from_date', '<=', $startOfMonth)
                            ->where('paid_to_date', '>=', $endOfMonth);
                    })
                    ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                        $q->where('unpaid_from_date', '<=', $startOfMonth)
                            ->where('unpaid_to_date', '>=', $endOfMonth);
                    });
            })
            ->get();
    
        // Prepare attendance data
        $attendanceData = [];
        // Rekey the $ticketWorks collection by 'work_start_date'
        $ticketWorksKeyed = $ticketWorks->mapWithKeys(function ($item) {
            return [$item->work_start_date => $item];
        });
        
        foreach ($dates as $date) {
            // Default to 'Absent'
            $status = 'A';
        
            // If the date is greater than the current date
            if ($date > $currentDate) {
                // Check for future leave entries
                $leaveEntry = $leaveApplications->first(function ($leave) use ($date) {
                    return $date >= $leave->from_date && $date <= $leave->to_date;
                });
        
                $status = $leaveEntry ? 'L' : 'NA';
            } else {
                // For past and present dates, check if the engineer is on leave or present
                $leaveEntry = $leaveApplications->first(function ($leave) use ($date) {
                    return $date >= $leave->from_date && $date <= $leave->to_date;
                });
        
                if ($leaveEntry) {
                    $status = 'L'; // Leave
                } else if ($ticketWorksKeyed->has($date)) {
                    $status = 'P'; // Present
                }
            }
        
            // Add to attendance data
            $attendanceData[$date] = $status;
        }
        
        foreach ($dates as $date) {
            // Default to 'Absent'
            $status = 'A';
    
            // If the date is greater than the current date
            if ($date > $currentDate) {
                // Check for future leave entries
                $leaveEntry = $leaveApplications->first(function ($leave) use ($date) {
                    return $date >= $leave->from_date && $date <= $leave->to_date;
                });
    
                $status = $leaveEntry ? 'L' : 'NA';
            } else {
                // For past and present dates, check if the engineer is on leave or present
                $leaveEntry = $leaveApplications->first(function ($leave) use ($date) {
                    return $date >= $leave->from_date && $date <= $leave->to_date;
                });
    
                if ($leaveEntry) {
                    $status = 'L'; // Leave
                } else if ($ticketWorks->has($date)) {
                    $status = 'P'; // Present
                }
            }
    
            // Add to attendance data
            $attendanceData[$date] = $status;
        }
        // Return response as JSON
        return response()->json([
            'engineer' => $engineer,
            'attendance' => $attendanceData,
        ]);
    }

    public function getAttendanceDetail(string $id, $date)
    {
        // Convert the date to a Carbon instance to ensure it's formatted correctly
        $date = \Carbon\Carbon::parse($date)->format('Y-m-d');
        $currentDate = Carbon::now()->startOfDay(); // Get the current date starting at midnight
    
        // Get the TicketWork records filtered by user_id and work_start_date
        $daily_work = TicketWork::select('*')
                        ->where('user_id', $id)
                        ->whereDate('work_start_date', $date)
                        ->get();

        $uniqueTicketCount = TicketWork::where('user_id', $id)->whereDate('work_start_date', $date)
        ->distinct('ticket_id')
        ->count('ticket_id');

        $workBreak = '';
        foreach ($daily_work as $dailyTicketWork) {
            $dailyWorkBreak = WorkBreak::where('ticket_work_id', $dailyTicketWork->id)->first();
            if ($dailyWorkBreak) {
                $workBreak = $dailyWorkBreak->total_break_time;
            }
        }
        

        if ($date > $currentDate) {
            $status = 'NA';
        } else {
            $status = 'A';
        }
                                
        if($daily_work->isNotEmpty()) {
            $status = 'P';
        }

        $leaveEntry = EngineerLeave::where('engineer_id', $id)
        ->where(function ($query) use ($date) {
            $query->where(function ($q) use ($date) {
                $q->whereDate('paid_from_date', '<=', $date)
                    ->whereDate('paid_to_date', '>=', $date);
            })
            ->orWhere(function ($q) use ($date) {
                $q->whereDate('unpaid_from_date', '<=', $date)
                    ->whereDate('unpaid_to_date', '>=', $date);
            });
        })
        ->first();

        if($leaveEntry) {
            $status = 'L';
        }
    
        // Get the WorkBreak records filtered by engineer_id and work_date
        $breaks = WorkBreak::where('engineer_id', $id)
                           ->whereDate('work_date', $date)  // Filter by work_date
                           ->get();

        // Return the filtered data as a JSON response
        return response()->json([
            'status' => true,
            'data' => [
                'date' => $date,
                'availability' => $status == 'P' ? true : false,
                'check_in_time' => $daily_work->isNotEmpty() ? $daily_work->first()->start_time : null,
                'check_out_time' => $daily_work->isNotEmpty() ? $daily_work->last()->end_time : null,
                'break_time' => $workBreak,
                'total_tickets' => $uniqueTicketCount
            ]
        ]);

    }

    public function holidayList() {
        $engineer = Auth::guard('api_engineer')->user();
        
        $holidays = Holiday::where('country_name', $engineer->addr_country)->orderBy('date', 'asc')->get();
        return response()->json([
            'status' => true,
            'data' => HolidayResource::collection($holidays),
        ]);
    }
    
    public function getCalendarList($id, $startDate, $endData) {

        $currentDate = now()->format('Y-m-d');

        // Parse the start and end dates
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endData)->endOfDay();

        // Generate all dates within the given range
        $allDates = $startDate->diffInDays($endDate) + 1;
        $dates = collect(range(0, $allDates - 1))->map(function ($day) use ($startDate) {
            return $startDate->copy()->addDays($day)->format('Y-m-d');
        });

        // Fetch engineer details
        $engineer = Engineer::find($id);
        if (!$engineer) {
            return response()->json(['message' => 'Engineer not found'], 404);
        }

        $leaveApplications = EngineerLeave::where('engineer_id', $id)
        ->where('leave_approve_status', 'approved')
        ->where(function ($query) use ($startDate, $endDate) {
            $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('paid_from_date', [$startDate, $endDate])
                    ->orWhereBetween('paid_to_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('paid_from_date', '<=', $startDate)
                            ->where('paid_to_date', '>=', $endDate);
                    });
            })
            ->orWhere(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('unpaid_from_date', [$startDate, $endDate])
                    ->orWhereBetween('unpaid_to_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('unpaid_from_date', '<=', $startDate)
                            ->where('unpaid_to_date', '>=', $endDate);
                    });
            });
        })
        ->get();

        $ticketWorks = TicketWork::where('user_id', $id)
        ->where(function ($query) use ($startDate, $endDate) {
            $query->whereBetween('work_start_date', [$startDate, $endDate])
                ->orWhereBetween('work_end_date', [$startDate, $endDate])
                ->orWhere(function ($query) use ($startDate, $endDate) {
                    $query->where('work_start_date', '<=', $startDate)
                            ->where('work_end_date', '>=', $endDate);
                });
        })
        ->orderBy('work_start_date', 'asc')
        ->get()
        ->keyBy('work_start_date');

         // Fetch tickets for the engineer within the date range
        $tickets = Ticket::with(['ticketWork.breaks', 'ticketWork.workExpense', 'engCharge',   'ticketWork.workNote', 'engineer', 'customer', 'lead'])
        ->where('engineer_id', $id)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->get()
        ->groupBy(function ($ticket) {
            return Carbon::parse($ticket->created_at)->format('Y-m-d');
        });

        // Rekey the $ticketWorks collection by 'work_start_date'
        $ticketWorksKeyed = $ticketWorks->mapWithKeys(function ($item) {
            return [$item->work_start_date => $item];
        });

         // Prepare attendance data
        $attendanceData = [];

        foreach ($dates as $date) {
             // Fetch tickets for the engineer within the date range
            $tickets = Ticket::with(['ticketWork', 'ticketWork.breaks', 'engCharge', 'ticketWork.workExpense', 'ticketWork.workNote', 'engineer', 'customer', 'lead'])
            ->where('engineer_id', $id)
            ->whereDate('task_start_date', '<=', $date) // Start date is before or on $date
            ->whereDate('task_end_date', '>=', $date) 
            ->get();

            $status = '' ;

            // If the date is greater than the current date
            if ($date > $currentDate) {
                // Check for future leave entries
                $leaveEntry = $leaveApplications->first(function ($leave) use ($date) {
                    return $date >= $leave->from_date && $date <= $leave->to_date;
                });
    
                $status = $leaveEntry ? 'L' : 'NA';
            } else {
                // For past and present dates, check if the engineer is on leave or present
                $leaveEntry = $leaveApplications->first(function ($leave) use ($date) {
                    return $date >= $leave->from_date && $date <= $leave->to_date;
                });
    
                if ($leaveEntry) {
                    $status = 'L'; // Leave
                } else if ($ticketWorksKeyed->has($date)) {
                    $status = 'P'; // Present
                } else {
                    $status = 'A'; // Present
                }
            }

            // Sum total_break_time in seconds
            $totalBreakSeconds = WorkBreak::where('engineer_id', $id)
                ->where('work_date', $date)
                ->sum(DB::raw("TIME_TO_SEC(total_break_time)"));

            // Format the total seconds into H:i:s
            $formattedTotalBreakTime = gmdate('H:i:s', $totalBreakSeconds);

            $filteredWork = $tickets->pluck('ticketWork')
            ->flatten()
            ->filter(fn($work) => $work->work_start_date == $date);

            $checkIn = $filteredWork->isNotEmpty() ? $filteredWork->first()->start_time : null;
            $checkOut = $filteredWork->isNotEmpty() ? $filteredWork->last()->end_time : null;

            // Add to attendance data
            $attendanceData[$date] = [
                'tickets' => TicketResource::collection($tickets),
                'attendance_status' => $status,
                'check_in'          => $checkIn ,
                'check_out'         => $checkOut,
                'break_time'        => $formattedTotalBreakTime
            ];
        }

        return response()->json([
            'status' => true ,
            'data' => $attendanceData
        ]);
    }

}