<?php

namespace Desoft\DVoyager\Commands;

use Desoft\DVoyager\Generator;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dvoyager:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install and generate breads from dvoyager config file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Generator $generator)
    {
        //TODO Dividir el proceso de install para ir notificando al usuario, además de incluir en un futuro la opción de seleccionar el tipo de proyecto
        $generator->install();
    }
}