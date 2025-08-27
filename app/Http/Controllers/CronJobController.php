<?php

namespace App\Http\Controllers;

use App\Events\AppPushNotification;
use App\Models\Engineer;
use App\Models\LeaveBalance;
use App\Models\Ticket;
use App\Models\TicketNotifications;
use App\Services\NotificationServices;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;
use GGInnovative\Larafirebase\Facades\Larafirebase;
use Kreait\Firebase\Factory;

class CronJobController extends Controller
{
    public $notificationServices;

    public function __construct(
        NotificationServices $notificationServices
    )
    {
        $this->notificationServices = $notificationServices;
    }

    public function isLastDayOfMonth()
    {
        return Carbon::now()->isSameDay(Carbon::now()->endOfMonth());
    }

    public function monthlyLeaveAllotment()
    {

        Log::info([
            'CRON-JOB-EXECUTED'
        ]);

        if ($this->isLastDayOfMonth()) {

            Log::info([
                'Monthly-Leave-Balance-Updated'
            ]);

            $fullTimeEngineers = Engineer::where([
                'job_type' => 'full_time'
            ])->get();

            foreach ($fullTimeEngineers as $key => $engineer) {

                LeaveBalance::where('engineer_id', $engineer->id)->increment('balance', 1.66, [
                    'leave_credited_this_month' => 1.66
                ]);
            }
        }
    }

    public function monthlyLeaveBalanceUpdate() {}

    // Every 15 days (for dispatch & full-time engineers)
    // reminders about conduct, dress code, and best practices
    public function employeeConductReminder()
    {
        $engineers = Engineer::where(function ($query) {
            $query->where('job_type', 'full_time')
                ->orWhere('job_type', 'dispatch');
        })->get();

        foreach ($engineers as $key => $engineer) {
            $deviceToken = $engineer->device_token;

            // Send Notification
            // Save Notification detail 

        }
    }

    // 28, 1 ? if date is 28 then what ?
    public function confirmPlanedLeave()
    {
        $engineers = Engineer::where(['job_type' => 'full_time'])->get();
        foreach ($engineers as $key => $engineer) {
            $deviceToken = $engineer->device_token;

            // Send Notification
            // Save Notification detail 

        }
    }

    public function ticketInprogressReminder()
    {

        // engineer_id, ticket_id, notification_type ['one_hour_reminder'],
        // notification_time, 
        // send notification and update send time with +2HR after send

    }

    public function ticketStartReminder()
    {

        $nowInIST = Carbon::now()->setTimezone('Asia/Kolkata'); // Convert to IST
        $currentDate =  $nowInIST->toDateString(); // Get current date (YYYY-MM-DD)

        $currentTime = $nowInIST->toTimeString();

        Log::info([
            'currentDate' => $currentDate,
            'currentTime' => $currentTime,
        ]);

        // Fetch records where the date matches today and time is greater than current time
        $notifications = TicketNotifications::with('engineer')->where('date', $currentDate)
            ->where('time', '<', $currentTime)
            ->where('status', '!=', 'sent')
            ->get();

        // Process the notifications (e.g., send notifications, update status, etc.)
        foreach ($notifications as $notification) {

            // Get Ticket Detail Here

            if ($notification->notification_type == "in_progress_ticket") {
                // Ticket work starts every 1HR update this notification time to next hr until ticket stop
                // on ticket work end remove notification

            } else if ($notification->notification_type == "ticket_reminder") {
                // This is before 2 HR notification remove this after sent

            } else if ($notification->notification_type == "started") {
                // Ticket time passed but engineer not checkedIn Then send this

            }

            // $this->sendPushNotification($notification->engineer->device_token, 'XYZ', $notification->notification_text);

            // Your logic here...
            Log::info([
                'notification' => $notification
            ]);

            $notification->update(['status' => 'sent']);
        }
    }

    public function ticketNotificationsEveryMinute()
    {

        $nowInIST = Carbon::now()->setTimezone('Asia/Kolkata');
        $currentDate = $nowInIST->toDateString();       // "Y-m-d"
        $currentTime = $nowInIST->format('H:i:s');      // "H:i:s"

        Log::info([
            'currentDate' => $currentDate,
            'currentTime' => $currentTime,
        ]);

        // Fetch notifications due up to now and not sent
        $notifications = TicketNotifications::with('engineer')->where('date', '<=', $currentDate)
            ->where('time', '<=', $currentTime)
            ->where('status', '!=', 'sent')
            ->where('notification_type', '!=', 'in_progress_ticket')
            ->get();

        Log::info([
            $notifications
        ]);

        foreach ($notifications as $notification) {

            try {
                // ✅ Send notification logic here
                $this->sendPushNotification($notification->engineer->device_token, 'Ticket Reminder', $notification->notification_text);

                // ✅ Update notification status and last_send_time
                $notification->update([
                    'status' => 'sent',
                    'last_send_time' => $nowInIST,
                ]);

                Log::info("Notification ID {$notification->id} sent successfully.");
            } catch (\Exception $e) {
                Log::error("Failed to send notification ID {$notification->id}: " . $e->getMessage());
            }
        }
    }

    // every 15 Days
    public function guidelinesReminders()
    {

        $engineers = Engineer::orderBy('id', 'desc')->get();

        foreach ($engineers as $engineer) {

            $deviceToken = $engineer->device_token;

            $fcmFilePath = config('services.firebase.fcm_file');
            $factory = (new Factory)->withServiceAccount(base_path("/public/" . $fcmFilePath));
            $messaging = $factory->createMessaging();

            $title = "Guidelines for Onsite Engineers";
            $notificationText = "Please refer to the below guidelines to be followed by the engineers every time they visit the site. It has been observed that every few days we receive one or the other feedback from the client for the engineers going onsite and we come in a critical situation in facing them.
    
            Please do not take it personally, but it must be taken up very seriously and any deviation to any of the below mentioned points can invite unfavorable action.
    
            - Reach onsite 15 minutes prior to them shift/task time
    
            - Speak either in Local language or English only with users. No other language to be used.
    
            - Dressing must be proper - Business Formals, Shirt and Trouser, Smart Casuals only
    
            - Do not eat on desk - Follow CLEAN DESK POLICY
    
            - Be polite and humble to the users and help them with all that you can
    
            - Look Fresh and smell good. Wear fresh and ironed clothing.";

            $notification = [
                'title' => $title,
                'body' => $notificationText,
            ];

            $message = [
                'token' => $deviceToken,
                'notification' => $notification,
            ];

            try {
                $messaging->send($message); // Send the notification
            } catch (Exception $e) {
                // Log the error for debugging purposes
                logger()->error("Failed to send notification to engineer ID : " . $e->getMessage());
            }
        }


        Log::info('15_day_reminder');
    }

    public function applyPlanLeave()
    {

        $engineers = Engineer::orderBy('id', 'desc')->get();

        foreach ($engineers as $engineer) {

            $deviceToken = $engineer->device_token;

            $fcmFilePath = config('services.firebase.fcm_file');
            $factory = (new Factory)->withServiceAccount(base_path("/public/" . $fcmFilePath));
            $messaging = $factory->createMessaging();

            $title = "Planned Leave Confirmation";
            $notificationText = "Kindly confirm or apply if you have any upcoming planned leaves.";

            $notification = [
                'title' => $title,
                'body' => $notificationText,
            ];

            $message = [
                'token' => $deviceToken,
                'notification' => $notification,
            ];

            try {
                $messaging->send($message); // Send the notification
            } catch (Exception $e) {
                // Log the error for debugging purposes
                logger()->error("Failed to send notification to engineer ID : " . $e->getMessage());
            }
        }
    }


    public function inprogressTicketWorkUpdate()
    {

        $nowInIST = Carbon::now()->setTimezone('Asia/Kolkata');
        $currentDate = $nowInIST->toDateString();
        $currentTime = $nowInIST->format('H:i:s');

        // Fetch notifications due up to now and not sent
        $notifications = TicketNotifications::with('engineer')->where('date', '<=', $currentDate)
            ->where('time', '<=', $currentTime)
            ->where('notification_type', 'in_progress_ticket')
            ->where('is_repeat', true)
            ->where('status', '!=', 'sent')
            ->get();

        foreach ($notifications as $notification) {

            try {

                // ✅ Send notification logic here
                $this->sendPushNotification($notification->engineer->device_token, 'Ticket Work Status', $notification->notification_text);

                // ✅ Update notification status and last_send_time
                $notification->update([
                    'last_send_time' => $nowInIST,
                ]);

                // Convert time to Carbon before adding an hour
                $newTime = Carbon::createFromFormat('H:i:s', $notification->time)
                    ->addHour()
                    ->format('H:i:s');

                // Update record
                TicketNotifications::where('id', $notification->id)
                    ->update(['time' => $newTime]);

                Log::info("Notification ID {$notification->id} sent successfully.");
            } catch (\Exception $e) {

                Log::error("Failed to send notification ID {$notification->id}: " . $e->getMessage());
            }
        }
    }


    public function sendPushNotification($deviceToken, $title, $notificationText)
    {

        $fcmFilePath = config('services.firebase.fcm_file');
        $factory = (new Factory)->withServiceAccount(base_path("/public/" . $fcmFilePath));
        $messaging = $factory->createMessaging();

        $notification = [
            'title' => $title,
            'body' => $notificationText,
        ];

        $message = [
            'token' => $deviceToken,
            'notification' => $notification,
        ];

        try {
            $messaging->send($message); // Send the notification
            return $messaging;
        } catch (Exception $e) {
            // Log the error for debugging purposes
            logger()->error("Failed to send notification to engineer ID : " . $e->getMessage());
        }
    }


    public function monthlyPayoutStatusUpdate()
    {
        $currentMonth = now()->format('Y-m');

        Engineer::chunk(100, function ($engineers) use ($currentMonth) {
            foreach ($engineers as $engineer) {
                $monthlyPayouts = json_decode($engineer->monthly_payout, true) ?? [];
    
                // Set current month to 0
                $monthlyPayouts[$currentMonth] = 0;
    
                $engineer->monthly_payout = json_encode($monthlyPayouts);
                $engineer->save();
            }
        });
    
        return response()->json([
            'status' => true,
            'message' => 'Monthly payout status reset for all engineers for current month.'
        ]);
    }

    public function notificationTest()
    {
        
        // $notification_data = [
        //     'title' => 'TEST',
        //     'body' => 'TEST',
        //     'additional_data' => [
        //         'ticket_id' => 'id',
        //         'ticket_code' => 'AIM_TICK-12',
        //         'notify_type' => 'TICKETS',
        //     ]
        // ];

        // $engineer = Engineer::first();

        // event(new AppPushNotification($notification_data, $engineer));

        $fetch_ticket = Ticket::with(['engineer', 'customer'])->where('id', 228)->first();

        $notification_data = $this->notificationServices->offeredTickets($fetch_ticket);
        
        
        // dd($notification_data);

        // event(new AppPushNotification($notification_data, $fetch_ticket->engineer));

        // dd("log");
    }
}
