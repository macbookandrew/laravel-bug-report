<?php

namespace Tests\Feature;

use App\Jobs\NotifyRegistrants;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class EventDateTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_notifies_users_when_date_changes()
    {
        /** @var Event $event */
        $event = Event::factory()->create([
            'date' => now()->subDay(),
        ]);

        Queue::fake();

        $event->date = now();
        $event->save();

        Queue::assertPushed(NotifyRegistrants::class);
    }

    public function test_it_doesnt_notify_users_if_date_doesnt_change()
    {
        /** @var Event $event */
        $event = Event::factory()->create([
            'date' => now()->subDay(),
        ]);

        Queue::fake();

        $event->date = new Carbon($event->date); // Fails because $event->date is a now different Carbon instance from the original and HasAttributes currently checks strict equality between new and old custom date attributes.
        $event->save();

        Queue::assertNotPushed(NotifyRegistrants::class);
    }
}
