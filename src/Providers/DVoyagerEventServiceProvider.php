<?php 

namespace Desoft\DVoyager\Providers;

use Desoft\DVoyager\Events\DataDeleted;
use Desoft\DVoyager\Events\TableModified;
use Desoft\DVoyager\Events\UserAuth;
use Desoft\DVoyager\Listeners\SaveAuthTrace;
use Desoft\DVoyager\Listeners\SaveDeleteTrace;
use Desoft\DVoyager\Listeners\SaveTrace;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class DVoyagerEventServiceProvider extends ServiceProvider
{
    protected $listen = [
        DataDeleted::class => [
            SaveDeleteTrace::class
        ],
        TableModified::class => [
            SaveTrace::class
        ],
        UserAuth::class => [
            SaveAuthTrace::class
        ]
    ];

    public function boot()
    {
        parent::boot();
    }
}