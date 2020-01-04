<?php

namespace App\Listeners\Users;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * A new registered user will be receive his personal channel once his account creation has been completed.
 */

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
        /** 
        * We want to give the user his own channel on account creation.
        * This listener is being added to the Registered event which can be found in the EventServiceProvider class.
        */
        $event->user->channel()->create([
            'name' => $event->user->name
        ]);
    }
}
