<?php

namespace App\Http\Controllers;

use App\Models\Engineer;
use App\Models\EngineerLeave;
use App\Models\EngineerYearlyLeave;
use App\Models\TicketWork;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function index()
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => ""],
            ['name' => 'Attendace', 'url' => "/attendace"],
        ];
        $engineers = collect(Engineer::orderBy('id', 'DESC')->get())->toArray();
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
        return view('backend.attendance.index', [
            // 'data'   => [
            'engineers' => $engineers,
            'year' => $year,
            'month' => $month,
            'currentYear' => $currentYear,
            'currentMonth' => $currentMonth,
            'breadcrumbs' => $breadcrumbs,
            // ], 
        ]);
    }

    public function leaveIndex(Request $request)
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => ""],
            ['name' => 'Leave', 'url' => "/leave"],
        ];
        $leaves = EngineerLeave::with('engineer')->get();
        return view('backend.leaves.index', [
            'leaves' => $leaves,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    public function updateLeaveStatus(Request $request)
    {

        $updateData['leave_approve_status'] = $request->leave_approve_status;
        $ticket = EngineerLeave::findOrFail($request->leave_id)->update($updateData);

        if ($request->leave_approve_status == "approved") {
            $leave = EngineerLeave::findOrFail($request['leave_id']);
            $engineerId = $leave->engineer_id;
            $deviceToken = Engineer::findOrFail($engineerId)->device_token;

            // $fromDate = $leave->from_date;
            // $toDate = $leave->to_date;

            $fromDate = $leave->paid_from_date ?? $leave->unpaid_from_date;
            $toDate = $leave->paid_to_date ?? $leave->unpaid_to_date;
            $leaveType = $leave->paid_from_date ? "Paid Leave" : "Unpaid Leave";

            $factory = (new Factory)->withServiceAccount(base_path('/public/aimbizit-26cdc-firebase-adminsdk-e1hxo-e35b54c82c.json'));
            $messaging = $factory->createMessaging();

            $notification = [
                'title' => 'Leave Approved ✅',
                'body' => "Your leave request from {$fromDate} to {$toDate} has been approved. Enjoy your time off!",
            ];

            $message = [
                'token' => $deviceToken,
                'notification' => $notification,
            ];

            $messaging->send($message);
        }

        return redirect()->route('leaves.index');
    }

    function getAttendanceData($startOfMonth, $endOfMonth, $currentDate, $dates, $engineerId = null, $engineer = null)
    {
        // Generate all dates for the selected month

        // Fetch engineers (all engineers if no specific ID is provided)
        $usersQuery = Engineer::query();

        if ($engineerId) {
            $usersQuery->where('id', $engineerId);
        }

        if (!empty($engineer)) {
            $usersQuery->where(function ($query) use ($engineer) {
                $query->where('first_name', 'LIKE', "%{$engineer}%")
                    ->orWhere('last_name', 'LIKE', "%{$engineer}%")
                    ->orWhere('engineer_code', 'LIKE', "%{$engineer}%");
            });
        }

        $users = $usersQuery->get();

        // Get attendance records for engineers
        $ticketWorks = TicketWork::with('engineer')
            ->whereBetween('work_start_date', [$startOfMonth, $endOfMonth])
            ->orderBy('work_start_date', 'asc')
            ->get()
            ->groupBy(function ($work) {
                return $work->user_id . '_' . $work->work_start_date;
            })
            ->map(function ($grouped) {
                return $grouped->first();
            })
            ->groupBy('user_id');

        // Fetch leave applications for engineers with approved leave status
        $leaveApplications = EngineerLeave::where('leave_approve_status', 'approved')
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('paid_from_date', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('paid_to_date', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('unpaid_from_date', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('unpaid_to_date', [$startOfMonth, $endOfMonth]);
            })
            ->get()
            ->groupBy('engineer_id');

        $holidays = Holiday::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->pluck('date')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->toArray();

        // Prepare the attendance data
        $attendanceData = [];

        foreach ($users as $user) {
            $attendanceData[$user->id] = [];

            foreach ($dates as $date) {
                $carbonDate = Carbon::parse($date);

                // Skip if before job start date
                if ($date < $user->job_start_date) {
                    $attendanceData[$user->id][$date] = 'NA';
                    continue;
                }

                $isHoliday = in_array($date, $holidays);
                $isWeekend = $carbonDate->isWeekend();
                $isFutureDate = $date > $currentDate;
                $status = 'A'; // Default

                // Leave entry if available
                $leaveEntry = null;
                if ($leaveApplications->has($user->id)) {
                    $leaveEntry = $leaveApplications->get($user->id)->first(function ($leave) use ($date) {
                        return ($leave->paid_from_date && $date >= $leave->paid_from_date && $date <= $leave->paid_to_date) ||
                            ($leave->unpaid_from_date && $date >= $leave->unpaid_from_date && $date <= $leave->unpaid_to_date);
                    });
                }


                if ($isFutureDate) {
                    if ($isWeekend) {
                        $status = 'W';
                    } elseif ($isHoliday) {
                        $status = 'H';
                    } elseif ($leaveEntry) {
                        $status = 'L';
                    } else {
                        $status = 'NA';
                    }
                    /*if ($leaveEntry) {
                        $status = 'L';
                    } elseif ($isHoliday) {
                        $status = 'H';
                    } elseif ($isWeekend) {
                        $status = 'W';
                    } else {
                        $status = 'NA';
                    }*/
                } else {
                    // Check if present (worked)
                    $isPresent = false;
                    if ($ticketWorks->has($user->id)) {
                        $attendanceEntry = $ticketWorks->get($user->id)->where('work_start_date', $date)->first();
                        if ($attendanceEntry) {
                            $isPresent = true;
                        }
                    }

                    if ($isPresent) {
                        if ($isHoliday) {
                            $status = 'P';
                        } elseif ($isWeekend) {
                            $status = 'P';
                        } else {
                            $status = 'P';
                        }
                    } elseif ($leaveEntry) {
                        $status = 'L';
                    } else {
                        // Absent logic for holidays/weekends
                        if ($isHoliday) {
                            $status = 'H';
                        } elseif ($isWeekend) {
                            $status = 'W';
                        } else {
                            $status = 'A';
                        }
                    }
                }

                $attendanceData[$user->id][$date] = $status;
            }
        }


        return $attendanceData;
    }

    public function fetchTable(Request $request)
    {
        // Get filter values from the request (default to current year/month)
        $year = $request->input('year', now()->year); // Default to the current year
        $month = $request->input('month', now()->month); // Default to the current month
        $engineerId = $request->input('engineer_id') ?? null; // Optional filter (null if not provided)
        $engineer = $request->input('engineer');

        // Determine the start and end of the month based on the selected year and month
        $startOfMonth = Carbon::create($year, $month)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month)->endOfMonth();
        $currentDate = now()->format('Y-m-d');

        // Fetch engineers (all engineers if no specific ID is provided)
        $usersQuery = Engineer::query();

        if ($engineerId) {
            $usersQuery->where('id', $engineerId);
        }

        if (!empty($engineer)) {
            $usersQuery->where(function ($query) use ($engineer) {
                $query->where('first_name', 'LIKE', "%{$engineer}%")
                    ->orWhere('last_name', 'LIKE', "%{$engineer}%")
                    ->orWhere('engineer_code', 'LIKE', "%{$engineer}%");
            });
        }

        if($request->field_name_direction && $request->filter_direction)
        {
            $usersQuery->orderBy($request->field_name_direction, $request->filter_direction);
        }

        $users = $usersQuery->get();

        $allDates = $startOfMonth->daysInMonth;

        $dates = collect(range(1, $allDates))->map(function ($day) use ($startOfMonth) {
            return $startOfMonth->clone()->setDay($day)->format('Y-m-d');
        });

        $attendanceData = $this->getAttendanceData(
            $startOfMonth,
            $endOfMonth,
            $currentDate,
            $dates,
            $engineerId = null,
            $engineer = null
        );

        $totalMonthDates = !empty($dates) ? count($dates) : 0;

        $leaves = EngineerLeave::with('engineer')
            ->get();

        // Render the view with all engineers' attendance
        return view('backend.attendance.attendance-table', compact('users', 'attendanceData', 'totalMonthDates', 'leaves', 'year', 'month'))->render();
    }

    public function fetchPopup(Request $request)
    {
        $currentDate = now()->format('Y-m-d');
        $year = $request->year;
        $month = $request->month;
        $engineerId = $request->engineer_id;

        // Determine the start and end of the month based on the selected year and month
        $startOfMonth = Carbon::create($year, $month)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month)->endOfMonth();
        $currentDate = now()->format('Y-m-d');

        $engineer = Engineer::where([
            'id' => $engineerId
        ])->first();

        // Fetch engineers (all engineers if no specific ID is provided)
        $usersQuery = Engineer::query();

        if ($engineerId) {
            $usersQuery->where('id', $engineerId);
        }

        if (!empty($engineer)) {
            $usersQuery->where(function ($query) use ($engineer) {
                $query->where('first_name', 'LIKE', "%{$engineer}%")
                    ->orWhere('last_name', 'LIKE', "%{$engineer}%")
                    ->orWhere('engineer_code', 'LIKE', "%{$engineer}%");
            });
        }

        $users = $usersQuery->get();

        $allDates = $startOfMonth->daysInMonth;

        $dates = collect(range(1, $allDates))->map(function ($day) use ($startOfMonth) {
            return $startOfMonth->clone()->setDay($day)->format('Y-m-d');
        });

        $leaves = EngineerLeave::with('engineer')->where('engineer_id', $request->engineer_id)
            ->where(function ($query) use ($request) {
                $query->whereYear('paid_from_date', $request->year)
                    ->whereMonth('paid_from_date', $request->month)
                    ->orWhere(function ($q) use ($request) {
                        $q->whereYear('unpaid_from_date', $request->year)
                            ->whereMonth('unpaid_from_date', $request->month);
                    });
            })
            ->get();

        $monthName = Carbon::createFromFormat('m', $request->month)->format('F');

        $attendanceData =  $this->getAttendanceData($startOfMonth, $endOfMonth, $currentDate, $dates, $engineerId, null);

        $totalMonthDates = !empty($dates) ? count($dates) : 0;

        $presentCount = array_count_values($attendanceData[$engineerId])['P'] ?? 0;
        $leaveCount = array_count_values($attendanceData[$engineerId])['L'] ?? 0;

        $holidays = Holiday::whereBetween('date', [$startOfMonth, $endOfMonth])->get();

        $totalWorkTime = TicketWork::whereBetween('work_start_date', [$startOfMonth, $endOfMonth])
            ->where('user_id', $request->engineer_id)
            ->selectRaw("TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(total_work_time))), '%H:%i') as total_work_time")
            ->value('total_work_time');

        return view('backend.attendance.popup', compact('leaves', 'totalWorkTime', 'totalMonthDates', 'engineer', 'attendanceData', 'presentCount', 'leaveCount', 'monthName', 'holidays', 'year'))->render();
    }

    public function leaveAllot()
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => ""],
            ['name' => 'Leave Allot', 'url' => "/leave-allot"],
        ];

        return view('backend.leaves.leave_allot_form', [
            'breadcrumbs'   => $breadcrumbs
        ]);
    }

    public function storeLeaveAllot(Request $request)
    {
        $validatedData = $request->validate([
            "year"   => "required|numeric",
            "leave"   => "required|numeric",
        ]);

        $year = $request->year;

        // Check if leave allotment already exists
        $existingAllotment = EngineerYearlyLeave::where('year', $year)->first();

        if ($existingAllotment) {
            return redirect()->back()->with('toast', [
                'type'      => 'danger',
                'message'   => 'Leave allotment for the year ' . $year . ' already exists.',
            ]);
        }

        $balance = round(($request->leave * 8) / 12, 2);

        $engineers = Engineer::where('job_type', 'full_time')->get();

        // Using transactions for data consistency
        DB::transaction(function () use ($engineers, $year, $balance) {
            foreach ($engineers as $engineer) {
                $data = [];
                $months = range(1, 12);
                foreach ($months as $month) {
                    $data[] = [
                        'engineer_id' => $engineer->id,
                        'year' => $year,
                        'month' => $month,
                        'balance' => $balance,
                    ];
                }
                EngineerYearlyLeave::insert($data);
            }
        });

        return redirect()->route('engg.index')->with('toast', [
            'type'      => 'success',
            'message'   =>  'Leave allotment created successfully for all engineers.'
        ]);
    }
}
