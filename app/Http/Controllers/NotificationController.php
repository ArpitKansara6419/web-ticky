<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Engineer;
use App\Models\EngineerDocument;
use App\Models\RightToWork;
use App\Models\TechnicalCertification;
use App\Models\Ticket;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;

class NotificationController extends Controller
{
    public function getExpCertificate()
    {
        $today = now(); // Get today's date
        $nextFiveDays = now()->addDays(5); // Get the date 5 days from today

        // Fetch the user_ids (engineer ids) of certificates that expire within the date range
        $engineerIds = TechnicalCertification::whereDate('expire_date', '>', $today) // Expiry date greater than today
            ->whereDate('expire_date', '<=', $nextFiveDays) // Expiry date less than or equal to 5 days from today
            ->pluck('user_id'); // Get only the user_ids (engineer ids)

        // Fetch device tokens for engineers (users) based on user_ids
        $deviceTokens = Engineer::whereIn('id', $engineerIds) // Filter engineers by the user_ids
            ->pluck('device_token', 'id'); // Get device_token for each engineer with the user_id as the key

        if ($deviceTokens->isEmpty()) {
            return response()->json(['message' => 'No certificates are expiring within the next 5 days.']);
        }

        // Initialize Firebase Messaging
        $factory = (new Factory)->withServiceAccount(base_path('/public/aimbizit-26cdc-firebase-adminsdk-e1hxo-e35b54c82c.json'));
        $messaging = $factory->createMessaging();

        // Notification details
        $notification = [
            'title' => 'Certificate Expiry Alert 🚨',
            'body' => 'Your certification(s) will expire within the next 5 days. Please take necessary action.',
        ];

        // Send notifications to each device token
        foreach ($deviceTokens as $userId => $deviceToken) {
            if ($deviceToken) { // Ensure the device token exists
                $message = [
                    'token' => $deviceToken,
                    'notification' => $notification,
                ];

                try {
                    $messaging->send($message);
                    // ✅ Store notification in DB
                    AppNotification::create([
                        'engineer_id' => $userId,
                        'title' => $notification['title'],
                        'body' => $notification['body'],
                    ]);
                    // Log or handle successful notification
                    \Log::info("Notification sent to user ID: $userId, Device Token: $deviceToken");
                } catch (\Exception $e) {
                    // Log or handle failed notification
                    \Log::error("Failed to send notification to user ID: $userId, Device Token: $deviceToken. Error: " . $e->getMessage());
                }
            }
        }

        return response()->json(['message' => 'Notifications sent successfully!']);
    }


    public function getExpDoc()
    {
        $today = now(); // Get today's date
        $nextFiveDays = now()->addDays(5); // Get the date 5 days from today

        // Fetch the user_ids (engineer ids) of documents that expire within the date range
        $engineerIds = EngineerDocument::whereDate('expiry_date', '>', $today) // Expiry date greater than today
            ->whereDate('expiry_date', '<=', $nextFiveDays) // Expiry date less than or equal to 5 days from today
            ->pluck('user_id'); // Get only the user_ids (engineer ids)

        // Fetch device tokens for engineers (users) based on user_ids
        $deviceTokens = Engineer::whereIn('id', $engineerIds) // Filter engineers by the user_ids
            ->pluck('device_token', 'id'); // Get device_token for each engineer with the user_id as the key

        if ($deviceTokens->isEmpty()) {
            return response()->json(['message' => 'No documents are expiring within the next 5 days.']);
        }

        // Initialize Firebase Messaging
        $factory = (new Factory)->withServiceAccount(base_path('/public/aimbizit-26cdc-firebase-adminsdk-e1hxo-e35b54c82c.json'));
        $messaging = $factory->createMessaging();

        // Notification details
        $notification = [
            'title' => 'Document Expiry Alert 🚨',
            'body' => 'Your document(s) will expire within the next 5 days. Please take necessary action.',
        ];

        // Send notifications to each device token
        foreach ($deviceTokens as $userId => $deviceToken) {
            if ($deviceToken) { // Ensure the device token exists
                $message = [
                    'token' => $deviceToken,
                    'notification' => $notification,
                ];

                try {
                    AppNotification::create([
                        'engineer_id' => $userId,
                        'title' => $notification['title'],
                        'body' => $notification['body'],
                    ]);

                    $messaging->send($message);
                    // Log or handle successful notification
                    \Log::info("Notification sent to user ID: $userId, Device Token: $deviceToken");
                } catch (\Exception $e) {
                    // Log or handle failed notification
                    \Log::error("Failed to send notification to user ID: $userId, Device Token: $deviceToken. Error: " . $e->getMessage());
                }
            }
        }

        return response()->json(['message' => 'Notifications sent successfully!']);
    }



    public function getExpRightToWork()
    {
        $today = now(); // Get today's date
        $nextFiveDays = now()->addDays(5); // Get the date 5 days from today

        // Fetch the user_ids (engineer ids) of RightToWork records that expire within the date range
        $engineerIds = RightToWork::whereDate('expire_date', '>', $today)
            ->whereDate('expire_date', '<=', $nextFiveDays)
            ->pluck('user_id');

        // Fetch device tokens for engineers (users) based on user_ids
        $deviceTokens = Engineer::whereIn('id', $engineerIds)
            ->pluck('device_token', 'id');

        // Initialize Firebase
        $factory = (new Factory)->withServiceAccount(base_path('/public/aimbizit-26cdc-firebase-adminsdk-e1hxo-e35b54c82c.json'));
        $messaging = $factory->createMessaging();

        // Loop through device tokens and send notifications
        foreach ($deviceTokens as $engineerId => $deviceToken) {
            if ($deviceToken) { // Ensure deviceToken is not null
                $notification = [
                    'title' => 'Right to Work Document Expiry Alert 🚨',
                    'body' => "Your document is expiring soon. Please take necessary action to renew it.",
                ];

                $message = [
                    'token' => $deviceToken,
                    'notification' => $notification,
                ];

                try {

                    AppNotification::create([
                        'engineer_id' => $engineerId,
                        'title' => $notification['title'],
                        'body' => $notification['body'],
                    ]);
                    $messaging->send($message); // Send the notification
                } catch (Exception $e) {
                    // Log the error for debugging purposes
                    logger()->error("Failed to send notification to engineer ID {$engineerId}: " . $e->getMessage());
                }
            }
        }

        return response()->json(['message' => 'Notifications sent successfully!'], 200);
    }

    public function sendBirthdayNotifications()
    {
        $today = now()->format('m-d'); // Format today's date to match the birthdate format (month-day)

        // Fetch engineers whose birthdays are today
        $engineers = Engineer::whereRaw('DATE_FORMAT(birthdate, "%m-%d") = ?', [$today]) // Match month and day
            ->whereNotNull('device_token') // Ensure the engineer has a device token
            ->get(['id', 'first_name', 'last_name', 'device_token']); // Fetch relevant fields

        if ($engineers->isEmpty()) {
            return response()->json(['message' => 'No birthdays today.']);
        }

        // Initialize Firebase Messaging
        $factory = (new Factory)->withServiceAccount(base_path('/public/aimbizit-26cdc-firebase-adminsdk-e1hxo-e35b54c82c.json'));
        $messaging = $factory->createMessaging();

        foreach ($engineers as $engineer) {
            $notification = [
                'title' => '🎉 Happy Birthday 🎂',
                'body' => "Dear {$engineer->first_name} {$engineer->last_name}, wishing you a fantastic birthday filled with joy and success!",
            ];

            $message = [
                'token' => $engineer->device_token,
                'notification' => $notification,
            ];

            try {
                AppNotification::create([
                    'engineer_id' => $engineer->id,
                    'title' => $notification['title'],
                    'body' => $notification['body'],
                ]);
                $messaging->send($message);
                Log::info("Birthday notification sent to Engineer ID: {$engineer->id}, Name: {$engineer->first_name} {$engineer->last_name}");
            } catch (\Exception $e) {
                Log::error("Failed to send birthday notification to Engineer ID: {$engineer->id}. Error: " . $e->getMessage());
            }
        }

        return response()->json(['message' => 'Birthday notifications sent successfully!']);
    }


    public function sendWorkAnniversaryNotifications()
    {
        $today = now()->format('m-d'); // Format today's date to match the job_start_date format (month-day)

        // Fetch engineers whose work anniversaries are today
        $engineers = Engineer::whereRaw('DATE_FORMAT(job_start_date, "%m-%d") = ?', [$today]) // Match month and day
            ->whereNotNull('device_token') // Ensure the engineer has a device token
            ->get(['id', 'first_name', 'last_name', 'job_start_date', 'device_token']); // Fetch relevant fields

        if ($engineers->isEmpty()) {
            return response()->json(['message' => 'No work anniversaries today.']);
        }

        // Initialize Firebase Messaging
        $factory = (new Factory)->withServiceAccount(base_path('/public/aimbizit-26cdc-firebase-adminsdk-e1hxo-e35b54c82c.json'));
        $messaging = $factory->createMessaging();

        foreach ($engineers as $engineer) {
            // Calculate work duration in years
            $years = now()->diffInYears($engineer->job_start_date);

            $notification = [
                'title' => '🎉 Work Anniversary 🎉',
                'body' => "Congratulations {$engineer->first_name} {$engineer->last_name} on completing {$years} years with us! Your dedication and hard work are greatly appreciated.",
            ];

            $message = [
                'token' => $engineer->device_token,
                'notification' => $notification,
            ];

            try {

                AppNotification::create([
                    'engineer_id' => $engineer->id,
                    'title' => $notification['title'],
                    'body' => $notification['body'],
                ]);

                
                $messaging->send($message);
                Log::info("Work anniversary notification sent to Engineer ID: {$engineer->id}, Name: {$engineer->first_name} {$engineer->last_name}");
            } catch (\Exception $e) {
                Log::error("Failed to send work anniversary notification to Engineer ID: {$engineer->id}. Error: " . $e->getMessage());
            }
        }

        return response()->json(['message' => 'Work anniversary notifications sent successfully!']);
    }
}
