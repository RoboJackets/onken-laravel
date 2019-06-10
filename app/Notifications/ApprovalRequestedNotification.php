<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class ApprovalRequestedNotification extends Notification
{
    use Queueable;

    private $requisition_name;
    private $requisition_url;
    private $requester_name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($requisition_name, $requisition_url, $requester_name)
    {
        $this->requisition_name = $requisition_name;
        $this->requisition_url = $requisition_url;
        $this->requester_name = $requester_name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($notifiable->routeNotificationForSlack(null) != null) {
            return ['mail', 'slack'];
        } else {
            return ['mail'];
        }
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->subject('Approval Requested')
            ->markdown('notification.requisition.approvalrequested', [
                'requisition_name' => $this->requisition_name,
                'requisition_url' => $this->requisition_url,
                'requester_name' => $this->requester_name,
            ]);
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $name = $this->requisition_name;
        $url = $this->requisition_url;
        $requester = $this->requester_name;

        return (new SlackMessage)
            ->from('Onken')
            ->content('A requisition needs approval as it has no listed approvers.')
            ->attachment(function ($attachment) use ($name, $url, $requester) {
                $attachment->title($name, $url)
                    ->fields([
                        'Requested By' => $requester,
                    ]);
            });
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
