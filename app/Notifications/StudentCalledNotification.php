<?php

namespace App\Notifications;

use App\Models\Violation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StudentCalledNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Violation $violation)
    {
        //
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
            'title' => 'Anda dipanggil ke '.$this->violation->room_name,
            'message' => $this->violation->description,
            'url' => route('notifications.index'),
            'violation_id' => $this->violation->id,
            'room' => $this->violation->room,
        ];
    }
}
