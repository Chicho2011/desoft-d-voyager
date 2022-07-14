<?php

namespace Desoft\DVoyager\Commands;

use Desoft\DVoyager\Generator;
use Illuminate\Console\Command;

class MinInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dvoyager:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install only the necessary';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Generator $generator)
    {
        $generator->minimumInstall();
    }
}