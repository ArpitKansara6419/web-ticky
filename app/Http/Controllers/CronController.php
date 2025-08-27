<?php

namespace App\Http\Controllers;

use App\Enums\EngineerResponseEnum;
use App\Enums\TaskTypeEnum;
use App\Events\AppPushNotification;
use App\Http\Controllers\Api\TicketController;
use App\Models\Engineer;
use App\Models\EngineerCharge;
use App\Models\Holiday;
use App\Models\TaskReminder;
use App\Models\Ticket;
use App\Models\TicketWork;
use App\Models\WorkBreak;
use App\Services\NotificationServices;
use App\Services\TicketService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CronController extends Controller
{
    protected $ticketService;

    protected $notificationServices;

    public function __construct(
        TicketService $ticketService,
        NotificationServices $notificationServices
    ) {
        $this->ticketService = $ticketService;
        $this->notificationServices = $notificationServices;
    }

    public function manipulateTicketsIfNoActionTaken()
    {
        /**
         * Fetch passed date ticket who's on hold and break
         * then change to close
         */
        $today_date = Carbon::now()->format('Y-m-d');
        echo "<pre>";
        $tickets = Ticket::whereNotNull('engineer_id')
            // ->where('task_end_date', '<', $today_date)
            ->whereIn('status', ['hold', 'break', 'inprogress'])
            ->where('engineer_status', 'accepted')
            ->chunk(1000, function ($tickets) use ($today_date) {
                foreach ($tickets as $ticket) {

                    /**
                     * Manage In Progress Ticket
                     */
                    if($ticket->status === "inprogress") 
                    {
                        
                        echo "In Progress Response <br/>";
                        echo "ticket_id => " . $ticket->id . " <br/>";
                        /**
                         * today_time with timezone
                         * task_end_date, task_time, timezone
                         * 
                         * Check ticket date and time is in range
                         * check TODAY_date with 11.50 PM 11.59 PM then put ticket on hold
                         * 
                         * get last work_start_date and that start date only and end_time would engineer
                         * & ticket status inprogress to hold
                         */

                        $today_dt_tz = utcToTimezone(Carbon::now(), $ticket->timezone);

                        $ticket_end_dt_tz = utcToTimezone($ticket->task_end_date . ' ' . $ticket->task_time, $ticket->timezone);

                        $cron_start_run_time = "23:45:00";
                        $cron_end_run_time = "23:59:00";

                        // dd([
                        //     '$today_dt_tz' => $today_dt_tz,
                        //     '$ticket_end_dt_tz' => $ticket_end_dt_tz,
                        //     'condition1' => $today_dt_tz <= $ticket_end_dt_tz,
                        //     'condition2' => $today_dt_tz->copy()->format('H:i:s') >= $cron_start_run_time,
                        //     'condition3' => $today_dt_tz->copy()->format('H:i:s') <= $cron_end_run_time,
                        // ]);

                        if( 
                            $today_dt_tz <= $ticket_end_dt_tz && 
                            $today_dt_tz->copy()->format('H:i:s') >= $cron_start_run_time &&    
                            $today_dt_tz->copy()->format('H:i:s') <= $cron_end_run_time
                        )
                        {
                            
                            $engineerCharge = EngineerCharge::where('engineer_id', $ticket->engineer_id)->first();

                           
                            $fetch_ticket_work = TicketWork::where('ticket_id', $ticket->id)
                                    ->where('user_id', $ticket->engineer_id)
                                    ->whereNotNull('work_start_date')
                                    ->whereNull('work_end_date')
                                    ->orderBy('id', 'DESC')
                                    ->first();

                            if($fetch_ticket_work)
                            {
                                // $start = Carbon::parse($fetch_ticket_work->work_start_date . ' ' . $fetch_ticket_work->start_time);
                                // $end = Carbon::parse($fetch_ticket_work->work_start_date . ' ' . $engineerCharge->check_out_time);

                                // $breaks = WorkBreak::where('ticket_work_id', $fetch_ticket_work->ticket_work_id)->get();

                                // $workDate = $fetch_ticket_work->work_start_date;

                                // $isHoliday = Holiday::where('date', $workDate)->exists();
                                // $isWeekend = Carbon::parse($workDate)->isWeekend();

                                // $validatedData = $request = [
                                //     'ticket_work_id' => $fetch_ticket_work->id,
                                //     'end_time' => $engineerCharge->check_out_time,
                                //     'work_end_date'  => $fetch_ticket_work->work_start_date,
                                //     'note' => 'Automatic on hold',
                                //     'status' => 'hold',
                                //     // 'document_file' => null,
                                // ];


                                // $this->ticketService->calculateEngineerPayout(
                                //     $start,
                                //     $end,
                                //     $ticket,
                                //     $breaks,
                                //     $fetch_ticket_work,
                                //     $validatedData,
                                //     $request,
                                //     $isHoliday,
                                //     $isWeekend
                                // );
                        
                                // Calculate Customer Payable
                                // $this->ticketService->calculateCustomerPayable(
                                //     $start,
                                //     $end,
                                //     $ticket,
                                //     $breaks,
                                //     $fetch_ticket_work,
                                //     $validatedData,
                                //     $isHoliday,
                                //     $isWeekend
                                // );
                            }

                            if($today_dt_tz->copy()->format('Y-m-d') >= $ticket_end_dt_tz->copy()->format('Y-m-d')){
                                $result = $this->ticketService->closeTicket($ticket, 'close');
                            }else{
                                $result = $this->ticketService->closeTicket($ticket, 'hold');
                            }
                            
                        }
                        

                    }


                    // `work_breaks` needs to update : break_end_date, end_time // total_break_time
                    // `ticket_daily_work` end_time, end_date
                    // engineer/ticket-break/end
                    /**
                     * Request end
                     */

                    // if($ticket->task_end_date < $today_date) {
                        if ($ticket->status === "break") {
                            $get_ticket_break_ = WorkBreak::where('ticket_id', $ticket->id)
                                ->where('engineer_id', $ticket->engineer_id)
                                ->orderBy('id', 'DESC')
                                ->first();
                            if (isset($get_ticket_break_->id)) {
    
                                $res = $this->ticketService->holdAndBreakAndPassEndDateAction($ticket);
    
                                echo "Break Response <br/>";
                                echo "ticket_id => " . $ticket->id . " <br/>";
                                print_r($res);
                                echo "<br/>";

                                // ticket, status : close 
                                // engineer-ticket/end-work
                                $get_ticket_work_id = TicketWork::where('ticket_id', $ticket->id)
                                    ->where('user_id', $ticket->engineer_id)
                                    ->orderBy('id', 'DESC')
                                    ->first();
                                if (isset($get_ticket_work_id->id)) {
                                    if($ticket->task_end_date < $today_date) {
                                        $result = $this->ticketService->closeTicket($ticket, 'close');
                                    }else{
                                        $result = $this->ticketService->closeTicket($ticket, 'hold');
                                    }
                                    
                                    echo "Close Response <br/>";
                                    echo "ticket_id => " . $ticket->id . " <br/>";
                                    print_r(json_decode($result));
                                    echo "<br/>";
                                }
                            }
                        // }

                        if ($ticket->status === "hold" && $ticket->task_end_date < $today_date) {
                            $get_ticket_work_id = TicketWork::where('ticket_id', $ticket->id)
                                ->where('user_id', $ticket->engineer_id)
                                ->orderBy('id', 'DESC')
                                ->first();
                            if (isset($get_ticket_work_id->id)) {
                                $result = $this->ticketService->closeTicket($ticket, 'close');
                                echo "Close Response <br/>";
                                echo "ticket_id => " . $ticket->id . " <br/>";
                                print_r(json_decode($result));
                                echo "<br/>";
                            }
                        }
                    }
                    

                    
                }
            });

        /**
         * Fetch passed date ticket who's status accepted
         * then change to Expired
         */
        $today_date = Carbon::now()->format('Y-m-d');

        $tickets = Ticket::whereNotNull('engineer_id')
            ->where('task_end_date', '<', $today_date)
            ->where('engineer_status', 'accepted')
            // ->where('status', '<>', 'expired')
            ->whereNull('status')
            ->chunk(1000, function ($tickets) {
                foreach ($tickets as $ticket) {
                    // ticket, status : Expired
                    $ticket->status = "expired";
                    $ticket->save();

                    echo "Expired <br/>";
                    echo "ticket_id => " . $ticket->id . " <br/>";
                    echo "<br/>";
                }
            });
    }

    /**
     * Offered Tickets Crons
     *
     * @return void
     */
    public function offerredTicketNotificationsCron(): void
    {
        Ticket::with(['engineer', 'customer'])
            ->where('engineer_status', 'offered')
            ->where('offered_notification_date_time', '<=', now()->subMinutes(30))
            ->chunk(100, function ($offered_tickets) {
                foreach ($offered_tickets as $ticket) {

                    $this->notificationServices->offeredTickets($ticket);

                    $ticket->offered_notification_date_time = now();
                    $ticket->save();
                }
            });
    }

    public function dispatchTaskReminder()
    {
        /**
         * tickets : id, engineer_id, status (null) status is null, task_end_date, task_start_date, task_time
         * Fetch ticket if not started and before 2 hours send notification to engineer
         * if still not started before 30 minutes send notification to engineer
         * if not started every 15 min send notification to engineer
         * 
         * Another scenarion is ticket has 
         * task_end_date, task_start_date, 
         * task_time
         * 
         * Fetch task reminder
         * 1. fetch the progress tickets and engineer who have accepted
         * 2. Check the date with today
         * 3. check holiday and (taken) leave by engineer
         * 4. if valid
         * 5. send before 2 hours
         * 6. if give response on that notification then no need to send further
         * 7. If give response not given send again after 30 min
         * 8. if still not given response finale send after 15 min
         * 
         *  => New api called to send the response YES/NO
         */
        echo "<pre>";
        echo "------ CRON STARTED ----- ";
        echo "<br/>";
        // Firstly check the tickets
        $today_date_time = now();
        $today_date = $today_date_time->copy()->format('Y-m-d');

        $query = Ticket::with(['engineer', 'customer'])
            ->where(function ($query) {
                $query->where('status', '!=', 'close')
                    ->orWhereNull('status');
            })
            ->whereNotNull('engineer_id')
            ->whereNotNull('customer_id')
            ->whereDate('task_start_date', '<=', $today_date)
            ->whereDate('task_end_date', '>=', $today_date)
            ->where('engineer_status', 'accepted');

        $sql = $query->toSql();
        $bindings = $query->getBindings();

        echo "SQL: " . $sql . "\n";
        echo "<br/>";
        echo "Bindings: " . json_encode($bindings) . "\n";
        echo "<br/>";

        $query->chunk(100, function ($tickets) use ($today_date_time, $today_date) {
                foreach ($tickets as $ticket) {

                    /**
                     * WEEKEND - NO need to send reminder
                     * Check Holiday
                     */
                    $check_holiday = checkHoliday($today_date);
                    if ($check_holiday) {
                        continue;
                    }

                    if (in_array(now()->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
                        continue;
                    }

                    $ticket_start_date_time = $today_date . ' ' . $ticket->task_time;
                    $startDateTime = Carbon::parse($ticket_start_date_time);
                    $minutesUntilStart = $today_date_time->diffInMinutes($startDateTime, false);

                    /**
                     * If ticket created_at is today 
                     * & diff in minutes is less than 120 then no need to send any notifications
                     */
                    $ticket_created_at = Carbon::parse($ticket->created_at)->format('Y-m-d');
                    

                    // dd([
                    //     'startDateTime' => $startDateTime,
                    //     'minutesUntilStart' => $minutesUntilStart
                    // ]);
                    // if ($minutesUntilStart < 0) {
                    //     continue; // Task already started
                    // }
                    echo "TICKET DATA=>";
                    print_r([
                        'ticket_id' => $ticket->id. '---'. $ticket->ticket_code,
                        'timezone' => $ticket->timezone,
                        'engineer_timezone' => $ticket->engineer->timezone,
                        'task_start_date' => $ticket->task_start_date,
                        'task_end_date' => $ticket->task_end_date,
                        'task_time' => $ticket->task_time,
                    ]);
                    echo "<br/>";

                    Log::info("RUN the ticket _id for DISPATCH_CRON => ". $ticket->id);
                    echo "RUN the ticket _id for DISPATCH_CRON => ". $ticket->id;
                    echo "<br/>";

                    $task_reminders = TaskReminder::where('engineer_id', $ticket->engineer_id)
                        ->where('ticket_id', $ticket->id)
                        ->where('task_type', TaskTypeEnum::WORK_REMINDER_TO_ENGINEER->value);

                    // --- 1. 120 MINUTES ---
                    $reminder120 = (clone $task_reminders)
                        ->where('work_reminder_for', TaskTypeEnum::WORK_REMINDER_FOR_120->value)
                        ->first();

                    // dd($reminder120);
                    print_r([
                        'reminder120' => $reminder120 ? "TRUE" : "FALSE",
                        'minutesUntilStart' => $minutesUntilStart
                    ]);

                    if (
                        (!$reminder120 && $minutesUntilStart <= 120 && $ticket_created_at != $today_date) || 
                        (!$reminder120 && $ticket_created_at === $today_date && $minutesUntilStart <= 120 && $minutesUntilStart >= 59)
                    ) {

                        Log::info("RUN the ticket _id for DISPATCH_CRON 120 minute => ". $ticket->id);
                        echo "RUN the ticket _id for DISPATCH_CRON 120 minute => ". $ticket->id;
                        echo "<br/>";

                        $task_reminder = TaskReminder::create([
                            'engineer_id' => $ticket->engineer_id,
                            'customer_id' => $ticket->customer_id,
                            'ticket_id' => $ticket->id,
                            'task_type' => TaskTypeEnum::WORK_REMINDER_TO_ENGINEER->value,
                            'work_reminder_for' => TaskTypeEnum::WORK_REMINDER_FOR_120->value,
                            'reminder_at' => $today_date_time,
                        ]);

                        $this->notificationServices->reminderWorksToEngineer($ticket, $task_reminder);

                        continue;
                    }

                    if ($reminder120 && in_array($reminder120->user_response, ['Yes', 'No'])) {
                        continue; // Stop further reminders
                    }

                    // --- 2. 30 MINUTES ---
                    $reminder60 = (clone $task_reminders)
                        ->where('work_reminder_for', TaskTypeEnum::WORK_REMINDER_FOR_60->value)
                        ->first();

                    print_r([
                        'reminder60' => $reminder60 ? "TRUE" : "FALSE",
                        'minutesUntilStart' => $minutesUntilStart
                    ]);

                    if (
                        (!$reminder60 && $minutesUntilStart <= 59 && $ticket_created_at != $today_date) || 
                        (!$reminder60 && $ticket_created_at === $today_date && $minutesUntilStart <= 59 && $minutesUntilStart >= 29)
                    ) {
                        Log::info("RUN the ticket _id for DISPATCH_CRON 60 minute => ". $ticket->id);
                        echo "RUN the ticket _id for DISPATCH_CRON 60 minute => ". $ticket->id;
                        echo "<br/>";

                        $task_reminder = TaskReminder::create([
                            'engineer_id' => $ticket->engineer_id,
                            'customer_id' => $ticket->customer_id,
                            'ticket_id' => $ticket->id,
                            'task_type' => TaskTypeEnum::WORK_REMINDER_TO_ENGINEER->value,
                            'work_reminder_for' => TaskTypeEnum::WORK_REMINDER_FOR_60->value,
                            'reminder_at' => $today_date_time,
                        ]);
                        $this->notificationServices->reminderWorksToEngineer($ticket, $task_reminder);
                        continue;
                    }

                    if ($reminder60 && in_array($reminder60->user_response, ['Yes', 'No'])) {
                        continue; // Stop further reminders
                    }

                    // --- 3. 30 MINUTES ---
                    $reminder30 = (clone $task_reminders)
                        ->where('work_reminder_for', TaskTypeEnum::WORK_REMINDER_FOR_30->value)
                        ->first();
                    print_r([
                        'reminder30' => $reminder30 ? "TRUE" : "FALSE",
                        'minutesUntilStart' => $minutesUntilStart
                    ]);

                    if (!$reminder30 && $minutesUntilStart <= 30) {
                        Log::info("RUN the ticket _id for DISPATCH_CRON 30 minute => ". $ticket->id);
                        echo "RUN the ticket _id for DISPATCH_CRON 30 minute => ". $ticket->id;
                        echo "<br/>";
                        $task_reminder = TaskReminder::create([
                            'engineer_id' => $ticket->engineer_id,
                            'customer_id' => $ticket->customer_id,
                            'ticket_id' => $ticket->id,
                            'task_type' => TaskTypeEnum::WORK_REMINDER_TO_ENGINEER->value,
                            'work_reminder_for' => TaskTypeEnum::WORK_REMINDER_FOR_30->value,
                            'reminder_at' => $today_date_time,
                        ]);
                        $this->notificationServices->reminderWorksToEngineer($ticket, $task_reminder);
                    }
                }
            });
    }

    public function inProgressTicketReminderForWorkUpdate()
    {
        /**
         * Reminder: Please share the latest update Every 2HR.
         * 
         * Reminder: If pass the checkout time, then notification to close/hold the task.
         */
        $today_date_time = now();
        $today_date = $today_date_time->copy()->format('Y-m-d');
        Ticket::with(['engineer', 'customer', 'engineer.enggCharge'])
            ->whereIn('status', ['hold', 'inprogress'])
            ->whereNotNull('engineer_id')
            ->whereNotNull('customer_id')
            ->whereDate('task_start_date', '<=', $today_date)
            ->whereDate('task_end_date', '>=', $today_date)
            ->chunk(100, function ($tickets) use ($today_date_time, $today_date) {
                foreach ($tickets as $ticket) {
                    $engineerCharge = $ticket->engineer->enggCharge;

                    if (!$engineerCharge) continue;

                    /**
                     * WEEKEND - NO need to send reminder
                     * Check Holiday
                     */
                    $check_holiday = checkHoliday($today_date);
                    if ($check_holiday) {
                        continue;
                    }

                    if (in_array(now()->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
                        continue;
                    }

                    $engineer_start_time = Carbon::parse($today_date . ' ' . $ticket->engineer->enggCharge->check_in_time);
                    $engineer_end_time = Carbon::parse($today_date . ' ' . $ticket->engineer->enggCharge->check_out_time);

                    $ticket_start_time = Carbon::parse($today_date . ' ' . $ticket->task_time);
                    $ticket_end_time = Carbon::parse($today_date . ' ' . $ticket->task_time);

                    /*$check_ticket_created = Carbon::parse($ticket->created_at); 
                    if ($check_ticket_created->diffInHours($today_date_time) <= 2) {
                        continue;   
                    }*/


                    // 1. Check if engineer is on duty now
                    if ($today_date_time->lt($engineer_start_time) || $today_date_time->gt($engineer_end_time)) {
                        continue;
                    }

                    // 2. Check if task_end_time is in future
                    if ($ticket->task_end_date && Carbon::parse($ticket->task_end_date)->endOfDay()->lt($today_date_time)) {
                        continue;
                    }

                    // 3. Check if within 60 minutes of task start time
                    if ($today_date_time->between($ticket_start_time, $ticket_start_time->copy()->addMinutes(60))) {

                        // Find or create reminder record
                        $reminder = TaskReminder::where('ticket_id', $ticket->id)
                            ->where('task_type', TaskTypeEnum::WORK_UPDATE_REMINDER->value)
                            ->whereDate('reminder_at', $today_date)
                            ->first();

                        // If no reminder yet OR 60 minutes passed since last reminder
                        if (!$reminder || Carbon::parse($reminder->reminder_at)->addMinutes(60)->lte($today_date_time)) {

                            $task_reminder = TaskReminder::create([
                                'engineer_id' => $ticket->engineer_id,
                                'customer_id' => $ticket->customer_id,
                                'ticket_id' => $ticket->id,
                                'task_type' => TaskTypeEnum::WORK_UPDATE_REMINDER->value,
                                'work_reminder_for' => TaskTypeEnum::WORK_REMINDER_FOR_60->value,
                                'reminder_at' => $today_date_time,
                            ]);
                            $this->notificationServices->remindForWorkUpdate($ticket, $task_reminder);
                        }
                    }
                }
            });
    }

    public function tickeCloseHoldReminder()
    {
        /**
         * Reminder: 1 Hour/30 Min Before the checkout time, to close/hold the task if needed.
         */
        $today_date_time = now();
        $today_date = $today_date_time->copy()->format('Y-m-d');
        Engineer::with(['enggCharge'])
            ->whereNotNull('id')
            ->chunk(100, function ($engineers) use ($today_date_time, $today_date) {
                foreach ($engineers as $engineer) {
                    /**
                     * WEEKEND - NO need to send reminder
                     * Check Holiday
                     */
                    $check_holiday = checkHoliday($today_date);
                    if ($check_holiday) {
                        continue;
                    }

                    if (in_array(now()->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
                        continue;
                    }

                    $check_reminder = TaskReminder::where('engineer_id', $engineer->id)
                        ->whereDate('reminder_at', $today_date)
                        ->where('task_type', TaskTypeEnum::ENGINEER_WORK_CLOSE_REMINDER->value)
                        ->first();

                    $engineer_start_date_time = $today_date . ' ' . $engineer->enggCharge->check_out_time;
                    $startDateTime = Carbon::parse($engineer_start_date_time);
                    $minutesUntilStart = $today_date_time->diffInMinutes($startDateTime, false);

                    $ticket = Ticket::where('engineer_id', $engineer->id)
                                ->where('status', 'inprogress')->first();


                    if (!$check_reminder && $minutesUntilStart <= 30 && $ticket) {
                        // Send Reminder
                        $task_reminder = TaskReminder::create([
                            'engineer_id' => $engineer->id,
                            // 'customer_id' => $ticket->customer_id,
                            // 'ticket_id' => $ticket->id,
                            'task_type' => TaskTypeEnum::ENGINEER_WORK_CLOSE_REMINDER->value,
                            'work_reminder_for' => TaskTypeEnum::WORK_REMINDER_FOR_30->value,
                            'reminder_at' => $today_date_time,
                        ]);
                        $this->notificationServices->remindToEngineerForClose($engineer, $task_reminder);
                    }
                }
            });
    }
}
