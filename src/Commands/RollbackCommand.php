<?php

namespace Desoft\DVoyager\Commands;

use Desoft\DVoyager\Generator;
use Illuminate\Console\Command;

class RollbackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dvoyager:rollback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback generated resources';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Generator $generator)
    {
        $generator->rollbackGeneration();
    }
}