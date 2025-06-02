<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DepartmentDeletedNotification extends Notification
{
    use Queueable;

    public $department;

    public function __construct($department)
    {
        $this->department = $department;
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
            'message' => 'Se ha eliminado el departamento: '.$this->department->name,
            'host_id' => $this->department->id,
            'ip' => null,
            'action_url' => '#',
            'icon' => 'fa-building',
            'type' => 'department-updated'
        ];
    }
}
