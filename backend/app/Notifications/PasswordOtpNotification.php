<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class PasswordOtpNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private readonly string $otp
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Password Reset OTP')
            ->greeting("Hello, {$notifiable->name}!")
            ->line('You requested a password reset.')
            ->line(new HtmlString(
                '<p>Your One-Time Password (OTP) is:</p>
             <h2 style="margin: 16px 0; font-weight: bold;">' . e($this->otp) . '</h2>
             <p>This OTP will expire in 10 minutes.</p>'
            ))
            ->line('If you did not request this password reset, please ignore this email.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
