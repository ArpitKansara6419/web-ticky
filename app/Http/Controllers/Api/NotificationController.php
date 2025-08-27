<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use App\Models\Ticket;
use App\Models\Engineer;
use App\Models\TicketNotifications;
use Carbon\Carbon;
use Google\Rpc\Context\AttributeContext\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{

    public function updateDailyNotification(Request $request)
    {
        $today = Carbon::today()->toDateString(); // Get today's date as 'YYYY-MM-DD'
        Log::info("Today's Date: " . $today);

        // ✅ Change Query to Match Date Only
        $tickets = Ticket::whereRaw('DATE(task_start_date) = ?', [$today])->get();
        Log::info("Tickets found: " . $tickets->count());

        if ($tickets->isEmpty()) {
            return response()->json(['message' => 'No tickets found for today.'], 200);
        }

        //
        foreach ($tickets as $ticket) {

            if (!$ticket->task_time) {
                Log::warning("Skipping Ticket ID {$ticket->id}: task_time is missing.");
                continue;
            }

            // Combine task_start_date and task_time
            $taskStartDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $ticket->task_start_date . ' ' . $ticket->task_time);
            $notificationTime = $taskStartDateTime->subHours(2);

            try {

                TicketNotifications::create([
                    'ticket_id' => $ticket->id,
                    'date' => $today,
                    'time' => $notificationTime->format('H:i:s'),
                    'notification_text' => 'Reminder: Ticket #' . $ticket->id . ' starts soon.',
                    'engineer_id' => $ticket->engineer_id,
                    'engineer_device_token' => $ticket->engineer->device_token ?? null,
                    'notification_type' => 'reminder',
                    'is_repeat' => 0,
                    'status' => 'pending',
                ]);

                Log::info("Notification created for Ticket ID: {$ticket->id}");
            } catch (\Exception $e) {

                Log::error("Failed to insert notification for Ticket ID {$ticket->id}: " . $e->getMessage());
            }
        }

        return response()->json(['message' => 'Daily notifications updated successfully!'], 200);
    }

    public function sendDailyNotification(Request $request)
    {
        try {
            $currentDate = Carbon::today()->format('Y-m-d'); // Get today's date
            $currentTime = Carbon::now()->format('H:i:s');  // Get current time

            // Find notifications where date & time match and status is pending
            $notifications = TicketNotifications::whereDate('date', $currentDate)
                ->whereTime('time', $currentTime)
                ->where('status', 'pending')
                ->get();

            foreach ($notifications as $notification) {
                // Get engineer's device token
                $engineer = Engineer::find($notification->engineer_id);
                $deviceToken = $engineer?->device_token;

                if (!empty($deviceToken)) {

                    // Firebase setup
                    // $factory = (new Factory)->withServiceAccount(base_path("/public/" . $fcmFilePath)));
                    // $factory = "";
                    // $messaging = $factory->createMessaging();

                    // $message = [
                    //     'token' => $deviceToken,
                    //     'notification' => [
                    //         'title' => 'Reminder: Upcoming Ticket',
                    //         'body' => $notification->notification_text,
                    //     ],
                    // ];

                    // $messaging->send($message);

                    // $notification->update(['status' => 'sent']);

                    Log::info('Notification sent for Ticket ID: ' . $notification->ticket_id);
                } else {
                    Log::warning('No device token found for Engineer ID: ' . $notification->engineer_id);
                }
            }

            return response()->json(['message' => 'Daily notifications sent successfully!'], 200);
        } catch (\Exception $e) {
            Log::error('FCM Notification Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send notifications'], 500);
        }
    }

    public function workNotStartNotification(Request $request) {}

    public function appNotificationsList(string $engineerId)
    {
        // Fetch all notifications for the given engineer, latest first
        $notifications = AppNotification::where('engineer_id', $engineerId)
            ->orderBy('created_at', 'desc')
            ->get([ 'title', 'body', 'created_at']);

        if ($notifications->isEmpty()) {
            return response()->json([
                'message' => 'No notifications found for this engineer.',
                'notifications' => [],
            ], 200);
        }

        return response()->json([
            'message' => 'Notifications retrieved successfully.',
            'notifications' => $notifications,
        ], 200);
    }
}
