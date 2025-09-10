<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationConfirmed extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Registration $registration)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $reg = $this->registration;
        $event = $reg->event()->first();
        return (new MailMessage)
            ->subject('Xác nhận đăng ký sự kiện')
            ->greeting('Chào '.$notifiable->name)
            ->line('Bạn đã đăng ký thành công sự kiện: '.$event->title)
            ->line('Mã check-in: '.$reg->checkin_code)
            ->action('Xem chi tiết', url('/'));
    }
}


