<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Auth\Notifications\ResetPassword as BaseResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class CustomResetPasswordNotification extends BaseResetPasswordNotification
{
    use Queueable;

    /**
     * Create a new notification instance.
     * @param string $token
     */
    public function __construct($token)
    {
        parent::__construct($token);
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $resetUrl = $this->resetUrl($notifiable);

        return (new MailMessage)
                    ->subject('Reset Password')
                    ->line('You are receiving this email because we received a password reset request for your account.')
                    ->action('Reset Password', $resetUrl)
                    ->line('If you did not request a password reset, no further action is required.');
    }

    /**
     * Build the password reset URL.
     *
     * @param mixed $notifiable
     * @return string
     */
    public function resetUrl($notifiable)
    {
        $app_url = env('REACT_APP_URL');

        return $app_url . '/reset-password/' . $this->token . '?email=' . urlencode($notifiable->getEmailForPasswordReset());;
    }
}
