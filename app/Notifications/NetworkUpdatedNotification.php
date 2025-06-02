<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NetworkUpdatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    

    public function __construct()
    {

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
            'message' => 'Se ha actualizado la configuración',
            'action_url' => route('configuracion.index'),
            'icon' => 'fa-network-wired',
            'type' => 'network-updated'
        ];
    }
}
