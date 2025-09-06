<?php

namespace App\Observers;

use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\DatabaseNotification as NotificationModel;

class DatabaseNotificationObserver
{
    public function deleting(NotificationModel $notification): void
    {
        Log::info('Deleting notification: ' . $notification->id);
    }

    public function deleted(NotificationModel $notification): void
    {
        Log::info('Deleted notification: ' . $notification->id);
    }

    public function saving(NotificationModel $notification): void
    {
        Log::info('Saving notification: ' . $notification->id);
    }
}
