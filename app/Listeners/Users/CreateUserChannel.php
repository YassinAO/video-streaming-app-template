<?php

namespace App\Listeners\Users;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateUserChannel
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $event->user->channel()->create([
            'name' => $event->user->name
        ]);
    }
}
