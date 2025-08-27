<?php

namespace App\Http\Controllers;

use App\DataTables\EngineerLeaveDataTable;
use Illuminate\Http\Request;
use App\Models\LeaveBalance;
use App\Models\EngineerLeave;
use App\Models\Engineer;
use App\Models\MonthlyLeaveBalance;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Kreait\Firebase\Factory;


class EngineerLeaveController extends Controller
{

    public function dashboard(Request $request)
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => ""],
            ['name' => 'Leave', 'url' => "/leave"],
        ];

        $leaves = EngineerLeave::with('engineer')->get();
        $leaveBalance = LeaveBalance::with('engineer')->get();

        // dd($leaveBalance->toArray());

        

    

        $leaveApproved = EngineerLeave::where([
            'leave_approve_status' => 'approved',
        ])->whereHas('engineer')
        ->sum('paid_leave_days');

        $leavePending = EngineerLeave::where([
            'leave_approve_status' => 'pending',
        ])->whereHas('engineer')->sum('paid_leave_days');

        $leaveCancelled = EngineerLeave::where([
            'leave_approve_status' => 'reject',
        ])->whereHas('engineer')->sum('paid_leave_days');

        $unpaid_leaveApproved = EngineerLeave::where([
            'leave_approve_status' => 'approved',
        ])->whereHas('engineer')
        ->sum('unpaid_leave_days');

        $unpaid_leavePending = EngineerLeave::where([
            'leave_approve_status' => 'pending',
        ])->whereHas('engineer')->sum('unpaid_leave_days');

        $unpaid_leaveCancelled = EngineerLeave::where([
            'leave_approve_status' => 'reject',
        ])->whereHas('engineer')->sum('unpaid_leave_days');

        $dashCounts = [
            'pending_leaves' => $leavePending,
            'approved_leaves' => $leaveApproved,
            'declined_leaves' => $leaveCancelled,
            'unpaid_pending_leaves' => $unpaid_leavePending,
            'unpaid_approved_leaves' => $unpaid_leaveApproved,
            'unpaid_declined_leaves' => $unpaid_leaveCancelled,
            'total_leaves' => (int)$leaveApproved + (int)$leavePending + (int)$leaveCancelled,
            'unpaid_total_leaves' => (int) $unpaid_leavePending + (int) $unpaid_leaveApproved + (int) $unpaid_leaveCancelled
        ];

        return view('backend.leaves.index', [
            'leaves' => $leaves,
            
            
            'leaveBalance' => $leaveBalance,
            'breadcrumbs' => $breadcrumbs,
            'dashCounts' => $dashCounts
        ]);
    }

    /**
     * Engineer Leave DataTable
     *
     * @param EngineerLeaveDataTable $engineerLeaveDataTable
     * @return JsonResponse
     */
    public function dataTable(EngineerLeaveDataTable $engineerLeaveDataTable) : JsonResponse
    {
        return $engineerLeaveDataTable->ajax();
    }

    public function updateLeaveStatus(Request $request)
    {

        $updateData['leave_approve_status'] = $request->leave_approve_status;
        $ticket = EngineerLeave::findOrFail($request->leave_id)->update($updateData);

        if ($request->leave_approve_status == "approved") {
            $leave = EngineerLeave::findOrFail($request['leave_id']);
            $engineerId = $leave->engineer_id;
            $deviceToken = Engineer::findOrFail($engineerId)->device_token;

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



    public function approve($id)
    {
        $leave = EngineerLeave::findOrFail($id);
        $engineerId = $leave->engineer_id;

        // Find Leave Balance record
        $leaveBalance = LeaveBalance::where('engineer_id', $engineerId)->first();

        if ($leaveBalance && $leave->leave_approve_status !== 'approved') {
            // Calculate leave usage
            $totalPaidLeave = (int) $leave->paid_leave_days;
            $totalUnpaidLeave = (int) $leave->unpaid_leave_days;

            // Update used leave count
            $leaveBalance->total_paid_leave_used += $totalPaidLeave;
            $leaveBalance->total_unpaid_leave_used += $totalUnpaidLeave;

            // Update total used leaves
            $leaveBalance->used_leaves = $leaveBalance->total_paid_leave_used + $leaveBalance->total_unpaid_leave_used;

            // Correct balance calculation (subtract only paid leaves)
            $leaveBalance->balance = max(0, round($leaveBalance->balance - $totalPaidLeave, 2));


            // Deduct from frozen leave balance
            $leaveBalance->freezed_leave_balance = max(0, $leaveBalance->freezed_leave_balance - $totalPaidLeave);


            // Save the updated leave balance
            $leaveBalance->save();
        }

        // Approve leave request
        $leave->update(['leave_approve_status' => 'approved']);

        return response()->json([
            'status' => true,
            'message' => 'Leave Approved Successfully.'
        ]);

        // return back()->with('success', 'Leave Approved Successfully');
    }



    public function reject($id)
    {
        $leave = EngineerLeave::findOrFail($id);
        $engineerId = $leave->engineer_id;

        // Find Leave Balance record
        $leaveBalance = LeaveBalance::where('engineer_id', $engineerId)->first();


        if ($leaveBalance && $leave->leave_approve_status === 'pending') {
            // Restore deducted leave
            $totalPaidLeave = (int) $leave->paid_leave_days;
            $totalUnpaidLeave = (int) $leave->unpaid_leave_days;

            // Ensure values don't go negative
            $leaveBalance->total_paid_leave_used = max(0, $leaveBalance->total_paid_leave_used - $totalPaidLeave);
            $leaveBalance->total_unpaid_leave_used = max(0, $leaveBalance->total_unpaid_leave_used - $totalUnpaidLeave);

            // Restore frozen leave balance

            $leaveBalance->freezed_leave_balance = max(0, $leaveBalance->freezed_leave_balance - $totalPaidLeave);

            // $leaveBalance->freezed_leave_balance = (int)$leaveBalance->freezed_leave_balance - $totalPaidLeave;
            
            // Save the updated leave balance
            $leaveBalance->save();
        }

        // Reject leave request
        $leave->update(['leave_approve_status' => 'reject']);

        return response()->json([
            'status' => true,
            'message' => 'Leave Rejected.'
        ]);

        // return back()->with('error', 'Leave Rejected');
    }






    public function getEngineerLeaveBalance($engineerId)
    {
        LeaveBalance::where([
            'engineer_id' => $engineerId
        ])->first();

        // return response()->json([])
    }
}
