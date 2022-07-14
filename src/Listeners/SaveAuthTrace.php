<?php

namespace Desoft\DVoyager\Listeners;

use Desoft\DVoyager\Events\UserAuth;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Desoft\DVoyager\Models\DVoyagerTrace;

class SaveAuthTrace
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DataDeleted  $event
     * @return void
     */
    public function handle(UserAuth $event)
    {
        $trace = new DVoyagerTrace();
        $trace->user = $event->user;
        $trace->action = $event->logIn ? 'Inició Sesión' : 'Cerró Sesión';

        $trace->save();
    }
}
