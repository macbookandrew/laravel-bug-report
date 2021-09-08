<?php

namespace App\Observers;

use App\Jobs\NotifyRegistrants;
use App\Models\Event;

class EventObserver
{
    /**
     * Handle the Event "updated" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function updated(Event $event)
    {
        if ($event->isDirty('date')) {
            NotifyRegistrants::dispatch($event);
        }
    }
}
