<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewChatMessage extends Notification
{
    // const SUBJECT = 'CosmeticRx: New message from your dermatologist';
    // const SUBJECT = 'CosmeticRx Updates: terms and conditions/privacy policy/other updates';
    // const SUBJECT = 'CosmeticRx.com - $29 Retin-A (Tretinoin) and/or $89 Latisse';
    const SUBJECT = 'Promo code: LOVE - $19 Retin-A (Tretinoin) shipment and/or $49 Latisse - The Best Annual Cosmetic Sale of any US company';

    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        return (new MailMessage)
                    ->view('emails.new-chat-message', ['notifiable'=>$notifiable])
                    ->subject(static::SUBJECT);
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
