<?php

namespace Desoft\DVoyager\Listeners;

use Desoft\DVoyager\Events\TableModified;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Desoft\DVoyager\Models\DVoyagerTrace;

class SaveTrace
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
    public function handle(TableModified $event)
    {
        $a = $event->method == 'POST' ? 'Creó' : ($event->method == 'PUT' ? 'Editó' : 'Borró');
        $data = $event->data->__toString() ? $event->data : '(desconocido)';
        $action = $a.' el elemento '.'"'.$data.'"'.' en la tabla '.$event->data->getTable();

        $trace = new DVoyagerTrace();
        $trace->user = $event->user;
        $trace->action = $action;

        $trace->save();
    }
}
