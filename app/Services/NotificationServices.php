<?php

namespace App\Services;

use App\Enums\TaskTypeEnum;
use App\Events\AppPushNotification;

class NotificationServices
{
    /**
     * Send Offered Tickets
     *
     * required relations : $ticket->customer, $ticket->engineer
     */
    public function offeredTickets($ticket)
    {
        $notification_data = [
            'title' => '#'.$ticket->ticket_code.' - New Ticket Assigned',
            'body' => 'Accept or reject the ticket.',
            'additional_data' => [
                'ticket_id' => (string)$ticket->id,
                'ticket_code' => $ticket->ticket_code,
                'notify_type' => 'offered_tickets_reminders',
            ]
        ];
        
        $return_event = event(new AppPushNotification($notification_data, $ticket->engineer));

        return $return_event;
    }

    /**
     * Send Reminder Work to Engineer
     *
     * required relations : $ticket->customer, $ticket->engineer
     */
    public function reminderWorksToEngineer($ticket, $task_reminder)
    {
        switch ((int) $task_reminder->work_reminder_for) {
            case 120:
                $title = '#'.$ticket->ticket_code.' ⏰ Starts in 2 Hours';
                $body = 'Work starts in 2 hrs. Will you reach on time?';
                break;
            case 30:
                $title = '#'.$ticket->ticket_code.' ⏰ 30 Mins to Start';
                $body = '30 mins left. Confirm if you’re on the way.';
                break;
            case 15:
                $title = '#'.$ticket->ticket_code.' ⏰ 15 Mins Left — Ready?';
                $body = '15 mins left. Please update your ETA.';
                break;
            default:
                $title = '#'.$ticket->ticket_code.' ⏰ Upcoming Work';
                $body = 'Please confirm your availability.';
        }
        $notification_data = [
            'title' => $title,
            'body' => $body,
            'additional_data' => [
                'ticket_id' => (string)$ticket->id,
                'ticket_code' => $ticket->ticket_code,
                'notify_type' => TaskTypeEnum::WORK_REMINDER_TO_ENGINEER->value,
                'task_id' => (string) $task_reminder->id
            ]
        ];
        
        $return_event = event(new AppPushNotification($notification_data, $ticket->engineer));

        return $return_event;
    }

    public function remindToEngineerForClose($engineer, $task_reminder)
    {
        $notification_data = [
            'title' => '⏳ Wrap-Up Reminder',
            'body'  => '30 minutes left. Please close or hold your open tickets before the day ends.',
            'additional_data' => [
                'notify_type' => TaskTypeEnum::ENGINEER_WORK_CLOSE_REMINDER->value,
                'engineer_id' => (string) $engineer->id,
                'task_id' => (string) $task_reminder->id
            ]
        ];
        
        $return_event = event(new AppPushNotification($notification_data, $engineer));

        return $return_event;
    }

    public function remindForWorkUpdate($ticket, $task_reminder)
    {
        $notification_data = [
            'title' => '#'.$ticket->ticket_code.' ⏳ Status Check',
            'body'  => 'Share your current work status for this ticket.',
            'additional_data' => [
                'notify_type' => TaskTypeEnum::WORK_UPDATE_REMINDER->value,
                'engineer_id' => (string) $ticket->engineer->id,
                'task_id' => (string) $task_reminder->id,
                'ticket_id' => (string)$ticket->id,
                'ticket_code' => $ticket->ticket_code,
            ]
        ];
        
        $return_event = event(new AppPushNotification($notification_data, $ticket->engineer));

        return $return_event;
    }
}
