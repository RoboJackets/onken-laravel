<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class VendorCreatedNotification extends Notification
{
    use Queueable;

    protected $vendor;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($vendor)
    {
        $this->vendor = $vendor;
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
        return (new MailMessage)->subject('Vendor created')
            ->markdown('notification.vendor.created', [
                'name' => $this->vendor->name,
                'url' => url(config('nova.path').'/resources/vendors/'.$this->vendor->id),
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
        $name = $this->vendor->name;
        $url = url(config('nova.path').'/resources/vendors/'.$this->vendor->id);

        return (new SlackMessage)
            ->from('Onken')
            ->content('A vendor was created.')
            ->attachment(function ($attachment) use ($name, $url) {
                $attachment->title($name, $url);
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
