<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Engineer;
use App\Models\Lead;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketWork;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\TicketResource;
use App\Http\Resources\DailyWorkNoteResource;
use App\Models\DailyWorkNote;
use App\Models\WorkBreak;
use App\Models\Holiday;
use App\Models\EngineerCharge;
use App\Models\EngineerExtraPay;
use App\Models\EngineerDailyWorkExpense;
use App\Models\CustomerPayable;
use App\Models\Notification;
use App\Models\TicketNotifications;
use App\Services\TicketService;
use Carbon\Carbon;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    public $ticketService;

    public function __construct(
        TicketService $ticketService
    )
    {
        $this->ticketService = $ticketService;
    }

    public function ticketList(Request $request)
    {
        $request_data = $request;

        // Start building the query
        $query = Ticket::with(['ticketWork.breaks', 'ticketWork.workExpense', 'engCharge', 'ticketWork.workNote', 'engineer', 'customer', 'lead'])->where('engineer_id', $request_data['engineer_id']);

        // Add status condition only if it's present
        if (!empty($request_data['engineer_status'])) {

            if ($request_data['engineer_status'] == 'offered' || $request_data['engineer_status'] == 'rejected') {
                $query->where('engineer_status', $request_data['engineer_status']);
            }

            if ($request_data['engineer_status'] == 'accepted') {
                $query->where('engineer_status', $request_data['engineer_status']);
                $query->where(function ($q) {
                    $q->where('status', '!=', 'close')
                        ->orWhereNull('status');
                });
            }
        } else {

            // if offered ->  only offered
            // if accepted -> all ticket execept close 
            // close -> only close
            // rejectd -> only rejected

            // Add status condition only if it's present
            if (!empty($request_data['status'])) {
                $query->whereIn('status', $request_data['status']);
            }
        }

        if (!empty($request_data['year']) && !empty($request_data['month'])) {
            $query->whereYear('task_start_date', $request_data['year'])
                ->whereMonth('task_start_date', $request_data['month']);
        }

        // Execute the query
        $ticketList = $query->get();

        // dd($ticketList);
        return response()->json([
            'data' => TicketResource::collection($ticketList)
        ]);
    }

    public function ticketDetail(Request $request, $ticket_id)
    {
        $ticket = Ticket::with(['ticketWork.breaks', 'ticketWork.workExpense', 'ticketWork.workNote', 'engCharge', 'engineer', 'customer', 'lead'])->where('id', $ticket_id)->first();

        return response()->json([
            'data' => new TicketResource($ticket)
        ]);
    }


    public function ticketStartWork(Request $request)
    {
        $validatedData = $request->validate([
            'ticket_id' => 'required|integer',
            'user_id' => 'required|integer',
            'work_start_date' => 'nullable',
            'start_time' => 'required|string',
            'address' => 'required|string',
            'status' => 'required|string',
        ]);

        $ticket = Ticket::find($validatedData['ticket_id']);

        if (!$ticket) {
            return response()->json([
                'message' => 'Ticket not found.',
            ], 400);
        }

        // Check if engineer's job type exists in Engineer model
        $engineer = Engineer::find($validatedData['user_id']);

        if (!$engineer || !$engineer->job_type) {
            return response()->json([
                'message' => 'Engineer job type is not complete.',
            ], 400);
        }

        // Check if engineer's charges exist in EngineerCharge model
        $engineerCharge = EngineerCharge::where('engineer_id', $validatedData['user_id'])->first();

        if (!$engineerCharge || (!$engineerCharge->monthly_charge && (!$engineerCharge->hourly_charge || !$engineerCharge->half_day_charge || !$engineerCharge->full_day_charge))) {
            return response()->json([
                'message' => 'Engineer charges are not complete.',
            ], 400);
        }

        // Check Ongoing Ticket
        $checkticket_count = Ticket::where('engineer_id', $engineer->id)
                    ->whereIn('status', ['inprogress'])
                    ->count();
        if($checkticket_count > 0)
        {
            return response()->json([
                'message' => 'Complete, current work before accepting new.',
            ], 400);
        }

        // Assuming $validatedData['start_time'] and $engineerCharge->check_in_time are in 'H:i:s' format
        $startTime = Carbon::createFromFormat('H:i:s', $validatedData['start_time']);
        $checkInTime = Carbon::createFromFormat('H:i:s', $engineerCharge->check_in_time);
        $checkOutTime = Carbon::createFromFormat('H:i:s', $engineerCharge->check_out_time);

        // Out of office hour start | is engineer check-in is earlier then regular check-in time
        if ($startTime->lessThan($checkInTime)) {
            $ticketStartData['is_out_of_office_hours'] = 1;
        } else if ($startTime->greaterThan($checkOutTime)) {
            $ticketStartData['is_out_of_office_hours'] = 1;
        } else {
            $ticketStartData['is_out_of_office_hours'] = 0;
        }

        // Out of office hour end
        $ticketStartData = [
            'ticket_id' => $validatedData['ticket_id'],
            'work_start_date' => $validatedData['work_start_date'], // Assuming today's date as `work_date`
            'start_time' => $validatedData['start_time'],
            'end_time' => null, // Set null if end_time is not provided
            'total_work_time' => null, // Can be calculated later
            'document_file' => null, // Set null if no document file provided
            'note' => null, // Set null if no note provided
            'address' => $validatedData['address'],
            'status' => $validatedData['status'],
        ];

        $ticketStartData['user_id'] = $validatedData['user_id'];

        $ticketWork = TicketWork::create($ticketStartData);

        /// -------------   Customer Payable Start -------------------

        unset($ticketStartData['user_id']);
        unset($ticketStartData['start_time']);

        $ticketStartData['ticket_work_id'] = $ticketWork->id;
        $ticketStartData['engineer_id'] = $validatedData['user_id'];
        $ticketStartData['customer_id'] = $ticket->customer_id;
        $ticketStartData['work_start_time'] = $validatedData['start_time'];
        $ticketStartData['currency'] = $ticket->currency_type;

        CustomerPayable::create($ticketStartData);

        /// -------------   Customer Payable End -------------------

        // Ticket status update to inprogress
        $ticket->update(['status' => 'inprogress']);
        


        // Fetch the updated ticket
        $ticketData = Ticket::with('engineer')->where('id', $validatedData['ticket_id'])->first();

        // Ensure start_time is properly formatted before parsing
        $startTime = isset($validatedData['start_time']) && !empty($validatedData['start_time'])
            ? Carbon::createFromFormat('H:i:s', trim($validatedData['start_time']))
            : now();

        $updatedTime = $startTime->copy()->addHours(1)->format('H:i:s');


        //   $tempStartTime = $startTime->copy()->addMinutes(5)->format('H:i:s');

        TicketNotifications::create([
            'ticket_id' => $ticketData->id,
            'date'  => now()->toDateString(), // Current date
            'time' => $updatedTime, // Ticket start time + 2 hours
            'notification_text'  => 'Kindly update work status.',
            'engineer_id'  => $ticketData->engineer_id ?? null, // Handle possible null values
            'engineer_device_token' => $ticketData->engineer->device_token ?? null, // Handle possible null values
            'notification_type' => 'in_progress_ticket', // Example notification type
            'is_repeat' => true, // Assuming default value is false
            'status' => 'pending', // Assuming default status
            'last_send_time' => now(), // Setting current timestamp
            'next_send_time' => now()->addHours(1), // Example: next notification in 1 hour
        ]);

        // Fetch the related engineer and ticket
        $ticket = Ticket::with('engineer')->find($request->ticket_id);
        if ($ticket && $ticket->engineer) {
            $engineerName = $ticket->engineer->first_name . ' ' . $ticket->engineer->last_name;
            $engineerCode = $ticket->engineer->engineer_code;

            Notification::create([
                'user_id' => $ticket->engineer_id ?? null,  // The engineer to notify
                'type' => 'ticket',  // The type of notification
                'title' => '🚀 Work Started on Ticket',  // Notification title
                'message' => "$engineerName ($engineerCode) started work on Ticket #{$ticket->ticket_code}.",  // The notification message
                'is_read' => false,  // By default, set it as unread
                'url' => null,  // URL, you can add a link if needed
                'meta' => json_encode([  // Store additional metadata like ticket ID
                    'ticket_id' => $ticket->id,
                ]),
            ]);
        }


        return response()->json([
            'message' => 'Ticket work start successfully.',
            'data' => $ticketWork,
        ]);
    }

    public function ticketEndWork(Request $request)
    {

        try {

            // Force JSON response
            $validatedData = $request->validate([
                'ticket_work_id' => 'required|exists:ticket_daily_work,id',
                'end_time' => 'required|date_format:H:i:s',
                'work_end_date'  => 'nullable',
                'note' => 'nullable|string|max:1000',
                'status' => 'nullable',
                'document_file' => 'nullable|file|mimes:pdf,jpg,png,doc,docx',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(), // Get validation errors as an array
            ], 422);
        }

        /// ---------------- Engineer Payout Calculations ------------------
        $ticketWork = TicketWork::findOrFail($validatedData['ticket_work_id']);
        $ticket = Ticket::findOrFail($ticketWork->ticket_id);

        // Assuming you have 'start_date' and 'end_date' along with 'start_time' and 'end_time'
        $start = Carbon::parse($ticketWork->work_start_date . ' ' . $ticketWork->start_time);
        $end = Carbon::parse($validatedData['work_end_date'] . ' ' . $validatedData['end_time']);
 
        $breaks = WorkBreak::where('ticket_work_id', $validatedData['ticket_work_id'])->get();

        if ($end->lessThanOrEqualTo($start)) {
            return response()->json([
                'success' => false,
                'message' => 'End time must be after start time.',
            ], 400);
        }

        $workDate = $ticketWork->work_start_date;

        $isHoliday = Holiday::where('date', $workDate)->exists();
        $isWeekend = Carbon::parse($workDate)->isWeekend();
        

        // Calculate Engineer Payout
        $this->ticketService->calculateEngineerPayout(
            $start,
            $end,
            $ticket,
            $breaks,
            $ticketWork,
            $validatedData,
            $request,
            $isHoliday,
            $isWeekend
        );

        // Calculate Customer Payable
        $this->ticketService->calculateCustomerPayable(
            $start,
            $end,
            $ticket,
            $breaks,
            $ticketWork,
            $validatedData,
            $isHoliday,
            $isWeekend
        );

        // update ticket status
        $ticket->update(['status' => $validatedData['status']]);

        $ticket = Ticket::with('engineer')->find($ticket->id);
        if ($ticket && $ticket->engineer) {
            $engineerName = $ticket->engineer->first_name . ' ' . $ticket->engineer->last_name;
            $engineerCode = $ticket->engineer->engineer_code;

            Notification::create([
                'user_id' => $ticket->engineer_id ?? null,
                'type' => 'ticket',
                'title' => '🎉 Work Completed on Ticket',
                'message' => "$engineerName ($engineerCode) completed work on Ticket #{$ticket->ticket_code}.",
                'is_read' => false,
                'url' => null,
                'meta' => json_encode([
                    'ticket_id' => $ticket->id,
                ]),
            ]);
        }


        TicketNotifications::where([
            'ticket_id' => $ticket->id,
            'date'  => now()->toDateString(), // Current date
            'engineer_id'  => $ticket->engineer_id, // Handle possible null values
            'notification_type' => 'in_progress_ticket', // Example notification type
            'is_repeat' => true, // Assuming default value is false
            'status' => 'pending', // Assuming default status
        ])->update([
            'is_repeat' => false,
            'status' => 'sent',
        ]);


        return response()->json([
            'status' => true,
            'message' => 'Ticket work updated successfully.',
        ]);
    }

    public function engWorkNote(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'nullable',
            'work_id' => 'required|integer',
            'note' => 'nullable|string',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',
            'status' => 'nullable|integer',
        ]);

        // Handle multiple file uploads
        $filePaths = []; // Array to hold file paths
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('ticket_work_documents', 'public');
                $filePaths[] = $path;
            }
        }

        if(isset($validatedData['id']))
        {
            $workNote = DailyWorkNote::find($validatedData['id']);

            if(!$workNote)
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Work note not found!',
                ], 404);
            }

            $workNote->update([
                'note' => $validatedData['note'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Work note updated successfully!',
                'data' => new DailyWorkNoteResource($workNote),
            ], 201);
            
        }

        $workNote = DailyWorkNote::create([
            'work_id' => $validatedData['work_id'],
            'note' => $validatedData['note'],
            'documents' => json_encode($filePaths),
            'status' => $validatedData['status'],
        ]);

        //  notification

        // Load ticket and engineer info via work_id relationship
        $work = TicketWork::with(['ticket.engineer'])->find($validatedData['work_id']);

        if ($work && $work->ticket && $work->ticket->engineer) {
            $engineer = $work->ticket->engineer;
            $ticketCode = $work->ticket->ticket_code;
            $engineerName = $engineer->first_name . ' ' . $engineer->last_name;
            $engineerCode = $engineer->engineer_code;

            Notification::create([
                'user_id' => $engineer->id,
                'type' => 'ticket_note',
                'title' => '📝 Engineer Note Added to Ticket',
                'message' => "$engineerName ($engineerCode) added a note to Ticket #$ticketCode",
                'is_read' => false,
                'url' => null,
                'meta' => json_encode([
                    'ticket_id' => $work->ticket->id,
                    'work_id' => $validatedData['work_id'],
                ]),
            ]);
        }


        return response()->json([
            'success' => true,
            'message' => 'Work note saved successfully!',
            'data' => new DailyWorkNoteResource($workNote),
        ], 201);
    }

    public function engWorkNoteList(Request $request)
    {
        // Fetch work notes from the database
        $workNotes = DailyWorkNote::all();

        // Check if records exist
        if ($workNotes->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No work notes found.',
            ], 404);
        }

        // Return the list of work notes
        return response()->json([
            'success' => true,
            'message' => 'Work notes retrieved successfully.',
            'data' => DailyWorkNoteResource::collection($workNotes),
        ], 200);
    }

    public function engTicketStatusUpdate(Request $request)
    {
        $ticket_id = $request->ticket_id;

        // Update ticket status
        Ticket::where('id', $ticket_id)->update([
            'engineer_status' => $request->engineer_status
        ]);

        // Fetch the updated ticket
        $ticketData = Ticket::with('engineer')->where('id', $ticket_id)->first();

        $engineer = $ticketData->engineer;
        $engineerName = $engineer ? $engineer->first_name . ' ' . $engineer->last_name : 'Engineer';
        $engineerCode = $engineer->engineer_code ?? 'N/A';
        $status = strtolower($request->engineer_status); // just in case

        // Custom message
        if ($status === 'accepted') {
            $title = '✅ Ticket Accepted Notification';
            $message = "$engineerName ($engineerCode) accepted Ticket #{$ticketData->ticket_code}";
        } elseif ($status === 'rejected') {
            $title = '❌ Ticket Rejected Notification';
            $message = "$engineerName ($engineerCode) rejected Ticket #{$ticketData->ticket_code}";
        } else {
            $title = 'ℹ️ Ticket Status Updated';
            $message = "Ticket {$ticketData->ticket_code} status changed to '{$request->engineer_status}'.";
        }

        Notification::create([
            'user_id' => $ticketData->engineer_id ?? null,
            'type' => 'ticket',
            'title' => $title,
            'message' => $message,
            'is_read' => false,
            'url' => null,
            'meta' => json_encode([
                'ticket_id' => $ticket_id,
                'status' => $request->engineer_status
            ]),
        ]);


        if ($ticketData && $request->engineer_status == 'accepted') {
            // Check if start_time exists, otherwise use current time as default

            $startTime = $ticketData->start_time ? Carbon::parse($ticketData->start_time) : now();
            $updatedTime = $startTime->copy()->subHours(2)->format('H:i:s');
            $tempStartTime = $startTime->copy()->addMinutes(5)->format('H:i:s');

            $startDate = Carbon::parse($ticketData->task_start_date);
            $endDate = Carbon::parse($ticketData->task_end_date);

            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {

                $notificationDate = $date->toDateString();

                TicketNotifications::create([
                    'ticket_id' => $ticketData->id,
                    'date'  => $notificationDate, // Current date
                    'time' => $updatedTime, // Ticket start time + 2 hours
                    'notification_text'  => 'Please share the below updates throughout the day 
                                            1) Message your ETA
                                            2) Message once you reach
                                            3) Message once you get access 
                                            4) Message once you start working with RE (name of RE)
                                            5) update on task every 30/60 mins
                                            6) Please share Pics as necessary
                                            7) Please get the SVR signed by site POC and share before leaving',
                    'engineer_id'  => $ticketData->engineer_id ?? null, // Handle possible null values
                    'engineer_device_token' => $ticketData->engineer->device_token ?? null, // Handle possible null values
                    'notification_type' => 'ticket_reminder', // Example notification type
                    'is_repeat' => false, // Assuming default value is false
                    'status' => 'pending', // Assuming default status
                    'last_send_time' => now(), // Setting current timestamp
                    'next_send_time' => now()->addHours(1), // Example: next notification in 1 hour
                ]);

                TicketNotifications::create([
                    'ticket_id' => $ticketData->id,
                    'date'  => $notificationDate, // Current date
                    'time' => $tempStartTime, // Ticket start time + 5 minute
                    'notification_text'  => 'ticket accpept test notification',
                    'engineer_id'  => $ticketData->engineer_id ?? null, // Handle possible null values
                    'engineer_device_token' => $ticketData->engineer->device_token ?? null, // Handle possible null values
                    'notification_type' => 'started', // Example notification type
                    'is_repeat' => false, // Assuming default value is false
                    'status' => 'pending', // Assuming default status
                    'last_send_time' => now(), // Setting current timestamp
                    'next_send_time' => now()->addHours(1), // Example: next notification in 1 hour
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Ticket Engineer status updated.',
        ], 200);
    }


    public function engTicketBreakStart(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ticket_work_id' => 'required', // Ensure ticket_work_id exists in the database
            'engineer_id' => 'required|exists:engineers,id',      // Ensure engineer_id exists in the database
            'break_start_date' => 'required',      // Ensure engineer_id exists in the database
            'work_date' => 'nullable|date',                      // Ensure work_date is a valid date
            'start_time' => ['required', 'date_format:H:i:s'],    // Ensure start_time is in HH:MM:SS format
            'status' => 'nullable',           // Ensure status is either 'active' or 'inactive'
        ]);

        $ticketWork = TicketWork::find($validatedData['ticket_work_id']);

        if (!$ticketWork) {
            return response()->json([
                'status' => false,
                'message' => 'Ticket work not found.',
            ], 404);
        }

        // Create the WorkBreak record
        $workBreak = WorkBreak::create([
            'ticket_work_id' => $validatedData['ticket_work_id'],
            'ticket_id' => $ticketWork->ticket_id,
            'engineer_id' => $validatedData['engineer_id'],
            'break_start_date' => $validatedData['break_start_date'],
            'start_time' => $validatedData['start_time'],
            'status' => $validatedData['status'],
        ]);

        Ticket::where('id', $ticketWork->ticket_id)->update(['status' => 'break']);

        // Get engineer and ticket details for the notification
        $ticket = Ticket::with('engineer')->find($ticketWork->ticket_id);
        if ($ticket && $ticket->engineer) {
            $engineerName = $ticket->engineer->first_name . ' ' . $ticket->engineer->last_name;
            $engineerCode = $ticket->engineer->engineer_code;

            // Create notification
            Notification::create([
                'user_id' => $ticket->engineer_id ?? null,
                'type' => 'ticket',
                'title' => '⏸️ Break Started on Ticket',
                'message' => "$engineerName ($engineerCode) started a break on Ticket #{$ticket->ticket_code}.",
                'is_read' => false,
                'url' => null,
                'meta' => json_encode([
                    'ticket_id' => $ticket->id,
                ]),
            ]);
        }

        // Return a success response
        return response()->json([
            'status' => true,
            'message' => 'Work break created successfully.',
            'data' => $workBreak,
        ], 201);
    }

    public function engTicketBreakEnd(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'break_end_date' => 'required',     // Ensure end_time is in HH:MM:SS format
            'end_time' => ['required', 'date_format:H:i:s'],     // Ensure end_time is in HH:MM:SS format
        ]);

        // Find the WorkBreak record by ID
        $workBreak = WorkBreak::find($request['work_break_id']);

        if (!$workBreak) {
            return response()->json([
                'status' => false,
                'message' => 'Work break not found.',
            ], 404);
        }

        // Calculate the total break time in minutes
        $startTime = strtotime($workBreak->start_time); // Convert start_time to timestamp
        $endTime = strtotime($validatedData['end_time']); // Convert end_time to timestamp

        if ($endTime <= $startTime) {
            return response()->json([
                'status' => false,
                'message' => 'End time must be after start time.',
            ], 400);
        }

        // Calculate the total break time in seconds
        $totalBreakTimeInSeconds = $endTime - $startTime;

        // Format the total break time as H:i:s
        $hours = floor($totalBreakTimeInSeconds / 3600);
        $minutes = floor(($totalBreakTimeInSeconds % 3600) / 60);
        $seconds = $totalBreakTimeInSeconds % 60;

        $totalBreakTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        // Update the WorkBreak record with end_time and total_break_time
        $workBreak->update([
            'break_end_date' => $validatedData['break_end_date'],
            'end_time' => $validatedData['end_time'],
            'total_break_time' => $totalBreakTime, // Store total break time in H:i:s format
        ]);

        Ticket::where('id', $workBreak->ticket_id)->update(['status' => 'inprogress']);

        // Get engineer and ticket details for the notification
        $ticket = Ticket::with('engineer')->find($workBreak->ticket_id);
        if ($ticket && $ticket->engineer) {
            $engineerName = $ticket->engineer->first_name . ' ' . $ticket->engineer->last_name;
            $engineerCode = $ticket->engineer->engineer_code;

            // Create notification
            Notification::create([
                'user_id' => $ticket->engineer_id ?? null,
                'type' => 'ticket',
                'title' => '⏹️ Break Ended on Ticket',
                'message' => "$engineerName ($engineerCode) ended the break on Ticket #{$ticket->ticket_code}.",
                'is_read' => false,
                'url' => null,
                'meta' => json_encode([
                    'ticket_id' => $ticket->id,
                ]),
            ]);
        }

        // Return a success response
        return response()->json([
            'status' => true,
            'message' => 'Work break ended successfully.',
            'data' => $workBreak,
        ]);
    }

    public function engTicketBreakList(Request $request)
    {
        // dd($request->all());

        // Start building the query
        $query = WorkBreak::query();

        // Apply filter by ticket_id if present
        if ($request->has('ticket_id')) {
            $query->where('ticket_id', $request['ticket_id']);
        }

        // Apply filter by ticket_work_id if present
        if ($request->has('ticket_work_id')) {
            $query->where('ticket_work_id', $request['ticket_work_id']);
        }

        // Fetch paginated results (you can adjust the pagination per your needs)
        $workBreaks = $query->get(); // Adjust pagination size if needed

        // Return the filtered list of work breaks
        return response()->json([
            'status' => true,
            'message' => 'Work breaks retrieved successfully.',
            'data' => $workBreaks,
        ]);
    }

    public function engineerDailyWorExpense(Request $request)
    {

        $documentFilePath = null;

        if ($request->hasFile('document')) {
            $documentFilePath = $request->file('document')->store('ticket_work_documents', 'public');
        }

        if(isset($request->id))
        {
            $workExpense = EngineerDailyWorkExpense::find($request->id);

            if(!$workExpense)
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Work expense not found!',
                ], 404);
            }

            $workExpense->update([
                'ticket_work_id' => $request->ticket_work_id,
                'engineer_id' =>  $request->engineer_id,
                'ticket_id' =>  $request->ticket_id,
                'name' =>  $request->name,
                'document' => $documentFilePath,
                'expense' => $request->expense,
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Work note updated successfully!',
                'data' => new DailyWorkNoteResource($workExpense),
            ], 201);
            
        }

        $engineerDailyWorkExpense = EngineerDailyWorkExpense::create([
            'ticket_work_id' => $request->ticket_work_id,
            'engineer_id' =>  $request->engineer_id,
            'ticket_id' =>  $request->ticket_id,
            'name' =>  $request->name,
            'document' => $documentFilePath,
            'expense' => $request->expense,
            'status' => $request->status,
        ]);

        // Fetch the related TicketWork record
        $ticketWork = TicketWork::find($request->ticket_work_id);
        if ($ticketWork) {
            // Add the expense value to the existing other_pay value
            $ticketWork->other_pay = ($ticketWork->other_pay ?? 0) + $request->expense;
            $ticketWork->save();
        }

        $ticket = Ticket::with('engineer')->find($request->ticket_id);
        if ($ticket && $ticket->engineer) {
            $engineerName = $ticket->engineer->first_name . ' ' . $ticket->engineer->last_name;
            $engineerCode = $ticket->engineer->engineer_code;

            Notification::create([
                'user_id' => $ticket->engineer_id ?? null,
                'type' => 'ticket',
                'title' => ' 💸 Expense Logged for Ticket',
                'message' => "$engineerName ($engineerCode) added an expense to Ticket #{$ticket->ticket_code}.",
                'is_read' => false,
                'url' => null,
                'meta' => json_encode([
                    'ticket_id' => $ticket->id,
                    'attachment' => $documentFilePath,
                ]),
            ]);
        }



        return response()->json([
            'success' => true,
            'message' => 'Daily expense saved successfully.',
        ], 200);
    }

    public function engineerDailyWorExpenseList(Request $request) {}

    public function remove($daily_work_note)
    {
        $daily_work_note = DailyWorkNote::find($daily_work_note);
        if(!$daily_work_note)
        {
            return response()->json([
                'status' => false,
                'message' => 'Daily work note not found.',
            ], 404);
        }
        $daily_work_note->delete();

        return response()->json([
            'status' => true,
            'message' => 'Removed your daily work note.',
        ], 200);

    }

    public function removeDailyWorkExpense($daily_work_expense)
    {
        $daily_work_expense_d = EngineerDailyWorkExpense::find($daily_work_expense);
        if(!$daily_work_expense_d)
        {
            return response()->json([
                'status' => false,
                'message' => 'Daily work expense not found.',
            ], 404);
        }
        $daily_work_expense_d->delete();

        return response()->json([
            'status' => true,
            'message' => 'Removed your daily work expense.',
        ], 200);
    }
}
