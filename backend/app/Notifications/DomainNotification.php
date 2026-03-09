<?php

namespace App\Notifications;

use App\Models\Domain;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class DomainNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Domain $domain)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['telegram', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->error()
            ->subject('Warning! Domain unavailable')
            ->line('Your domain {$this->domain->name} has stopped responding.')
            ->action('Check status', url('/domains'))
            ->line("We'll let you know when it's back online.");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'domain_id' => $this->domain->id,
            'name' => $this->domain->name,
            'message' => 'The domain is unavailable.',
        ];
    }

    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->to($notifiable->routeNotificationForTelegram())
            ->content("<b>⚠️ Внимание! Домен упал!</b>\n\n" .
                "Сайт: " . e($this->domain->name))
            ->options(['parse_mode' => 'HTML']);
    }
}
