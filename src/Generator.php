<?php

namespace Desoft\DVoyager;

use Desoft\DVoyager\Services\BreadGeneratorServices;
use Desoft\DVoyager\Services\ClassGeneratorServices;
use Desoft\DVoyager\Services\MigrationGeneratorServices;
use Desoft\DVoyager\Utils\GeneratorUtilities;
use Desoft\DVoyager\Utils\Utilities;
use Illuminate\Support\Facades\Artisan;

class Generator {

    private array $breads;
    private string $voyagerControllers;
    private string $dvoyagerControllers;

    public function __construct(
        private ClassGeneratorServices $classGeneratorServices,
        private MigrationGeneratorServices $migrationGeneratorServices,
        private BreadGeneratorServices $breadGeneratorServices
    )
    {
        $this->breads = config('dvoyager.breads');
        //TODO agregar al config
        $this->voyagerControllers = "TCG\\\Voyager\\\Http\\\Controllers";
        $this->dvoyagerControllers = 'Desoft\\DVoyager\\Http\\Controllers';
    }

    public function install()
    {
        $this->generateClass();
        $this->generateMigration();
        $this->executeMigrateCommand();
        $this->generateBreads();
        $this->changeVoyagerControllers();
    }

    public function generateClass()
    {
        foreach ($this->breads as $key => $value) {
            $info = $value['info'] ?? [];
            $this->classGeneratorServices->generateDVoyagerClass(
                name: $key, 
                table: $value['table'], 
                slugFrom: array_key_exists('slugFrom', $value) ? $value['slugFrom'] : 'title', 
                fieldsTranslatables: array_key_exists('fieldsTranslatable', $value) ? $value['fieldsTranslatable'] : '[]',
                fieldsInfo: json_encode($info)
            );
        }
    }

    public function generateMigration()
    {
        foreach ($this->breads as $key => $value) {
            $fields = $value['fields'];
            $this->migrationGeneratorServices->generateDVoyagerMigration($value['table'], $fields);
        }
    }

    public function generateBreads()
    {
        foreach ($this->breads as $key => $bread) {
            $this->breadGeneratorServices->createBread($bread, $key);
        }
    }

    public function executeMigrateCommand()
    {
        Artisan::call('migrate', [
            '--path' => '/database/migrations/DVoyager'
        ]);
    }

    public function changeVoyagerControllers()
    {
        $voyagerConfig = file_get_contents(base_path('config/voyager.php'));
        // dd($voyagerConfig);
        if(str_contains($voyagerConfig, $this->voyagerControllers))
        {
            $replacedVoyagerPath = str_replace($this->voyagerControllers, $this->dvoyagerControllers, $voyagerConfig);
            GeneratorUtilities::createFile(base_path('config/voyager.php'), $replacedVoyagerPath);
            Artisan::call('config:cache');
        }
    }

}