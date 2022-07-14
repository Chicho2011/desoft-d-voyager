<?php

namespace Desoft\DVoyager\Listeners;

use Desoft\DVoyager\Events\DataDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Desoft\DVoyager\Models\DVoyagerTrace;

class SaveDeleteTrace
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
    public function handle(DataDeleted $event)
    {
        $quantity = count($event->ids);
        $action = $event->action.' '.$quantity.' elementos de la tabla '.$event->table;

        $trace = new DVoyagerTrace();
        $trace->user = $event->user;
        $trace->action = $action;

        $trace->save();
    }
}
