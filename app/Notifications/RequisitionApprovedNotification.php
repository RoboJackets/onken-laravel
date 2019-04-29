<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RequisitionApprovedNotification extends Notification
{
    use Queueable;

    protected $requisition;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($requisition)
    {
        $this->requisition = $requisition;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $channels = [];
        if (config('app.admin_email') != null) {
            $channels[] = 'mail';
        }
        if (config('app.admin_slack_webhook_url') != null) {
            $channels[] = 'slack';
        }
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->subject('Requisition Approved')
            ->markdown('requisition.approval.approved', [
                'name' => $this->requisition->name,
                'url' => url(config('nova.path').'/resources/requisitions/'.$this->requisition->id),
                'vendor' => $this->requisition->vendor->name,
                'cost' => money_format('$%.2n', $this->requisition->amount),
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
        $name = $this->requisition->name;
        $url = url(config('nova.path').'/resources/requisitions/'.$requisition->id);
        $vendor = $this->requisition->vendor->name;
        $cost = $this->requisition->amount;

        return (new SlackMessage)
            ->from('Onken')
            ->content('A requisition was marked approved.')
            ->attachment(function ($attachment) use ($name, $url, $vendor, $cost) {
                $attachment->title($name, $url)
                    ->fields([
                        'Vendor' => $vendor,
                        'Total Cost' => money_format('$%.2n', $cost),
                        // TODO: mention overdraw here
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