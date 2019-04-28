<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

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
        return ['mail'];
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
            ->markdown('requisition.approval.requested', [
                'requisition_name' => $this->requisition_name,
                'requisition_url' => $this->requisition_url,
                'requester_name' => $this->requester_name,
            ]);
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
