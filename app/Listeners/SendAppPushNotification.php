<?php

namespace App\Listeners;

use App\Models\EngineerNotification;
use App\Models\TicketNotifications;
use GGInnovative\Larafirebase\Facades\Larafirebase;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendAppPushNotification
{
    protected $notification_data;

    protected $engineer;

    protected $notificationTitle;

    protected $notificationBody;

    protected $notificationadditionalData;

    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(object $event)
    {
        $this->notification_data = $event->notification_data;

        $this->engineer = $event->engineer;

        // Set All Data's
        $this->setAllDatas();

        $this->engineerNotificationStore();

        $v = $this->sendNotificationsToApp();

        return $v;
    }

    public function engineerNotificationStore()
    {
        return EngineerNotification::create([
            'engineer_id' => $this->engineer->id,
            'title' => $this->notificationTitle,
            'body' => $this->notificationBody,
            'notification_type' => $this->notification_data['additional_data']['notify_type'],
            'response_additional_data' => $this->notification_data['additional_data'],
        ]);
    }

    public function sendNotificationsToApp()
    {
        try {
            $firebaseResponse = Larafirebase::withTitle($this->notificationTitle)
            ->withBody($this->notificationBody)
            ->withAdditionalData($this->notificationadditionalData)
            ->withToken($this->engineer->device_token)
            // ->withToken('cwQxKpf770kblIMqTMDeVD:APA91bHMTTQduQStMAEZFy5vmnDdHN_pQjL0G0B3AZwbTL3HRuCwCgcobXx4pUQyjS8UEWzXk7twYtxF99n_HGZizpd8dZOs4fxSyLjq26v9-PltuItNH8E')
            ->sendNotification();

            Log::info('Firebase Notification Response : ' . print_r($firebaseResponse, true));

            return [
                'status' => $firebaseResponse->status(),
                'message' => $firebaseResponse->reason()
            ];
        
            // Log::error('Firebase Notification Exception: ' . print_r($firebaseResponse, true));

            // return $firebaseResponse->getBody();
        } catch (\Exception $e) {
            Log::error('Firebase Notification Exception: ' . $e->getMessage());
        }
    }

    public function setAllDatas()
    {
        $this->setTitle();
        $this->setBody();
        $this->setAdditionalData();
    }

    public function setTitle()
    {
        $this->notificationTitle = $this->notification_data['title'];
    }

    public function setBody()
    {
        $this->notificationBody = $this->notification_data['body'];
    }

    public function setAdditionalData()
    {
        $this->notificationadditionalData = $this->notification_data['additional_data'];
    }
}
