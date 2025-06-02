<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HostDeletedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $host;

    public function __construct($host)
    {
        $this->host = $host;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Se ha eliminado el host: '.$this->host->name,
            'host_id' => $this->host->id,
            'ip' => $this->host->ip,
            'action_url' => '#',
            'icon' => 'fa-server',
            'type' => 'host-deleted'
        ];
    }
}
