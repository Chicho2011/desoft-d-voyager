<?php

namespace Desoft\DVoyager;

use Desoft\DVoyager\Models\DVoyagerGenerationHistory;
use Desoft\DVoyager\Services\BreadGeneratorServices;
use Desoft\DVoyager\Services\ClassGeneratorServices;
use Desoft\DVoyager\Services\MigrationGeneratorServices;
use Desoft\DVoyager\Services\RelationshipGeneratorServices;
use Desoft\DVoyager\Utils\GeneratorUtilities;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use TCG\Voyager\Events\BreadDeleted;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Models\DataType;

class Generator {

    private array $breads;
    private string $voyagerControllers;
    private string $dvoyagerControllers;

    public function __construct(
        private ClassGeneratorServices $classGeneratorServices,
        private MigrationGeneratorServices $migrationGeneratorServices,
        private BreadGeneratorServices $breadGeneratorServices,
        private RelationshipGeneratorServices $relationshipGeneratorServices
    )
    {
        $this->breads = config('dvoyager.breads');
        //TODO agregar al config
        $this->voyagerControllers = "TCG\\\Voyager\\\Http\\\Controllers";
        $this->dvoyagerControllers = 'Desoft\\DVoyager\\Http\\Controllers';
    }

    public function install()
    {
        $this->executeClearConfigCache();
        $this->executeCommonMigrateCommand();
        $this->generate();
        $this->changeVoyagerControllers();
    }

    public function minimumInstall()
    {
        $this->executeClearConfigCache();
        $this->executeCommonMigrateCommand();
        $this->changeVoyagerControllers();
    }

    public function generate()
    {
        $migrationNumber = 0;
        foreach ($this->breads as $key => $value) {
            if(DVoyagerGenerationHistory::where('bread', $key)->first() == null)            
            {
                /*
                    Generar Clase
                */
                $info = $value['info'] ?? [];
                $relations = $this->searchForRelationships($value['fields']);
                //TODO Revisar el tema de los translatables
                $className = $this->classGeneratorServices->generateDVoyagerClass(
                    name: $key, 
                    table: $value['table'], 
                    slugFrom: array_key_exists('slugFrom', $value) ? $value['slugFrom'] : 'title', 
                    fieldsTranslatables: array_key_exists('fieldsTranslatable', $value) ? json_encode($value['fieldsTranslatable']) : '',
                    fieldsInfo: json_encode($info),
                    searchable: isset($value['searchable']) ? json_encode($value['searchable']) : '',
                    relationships: count($relations) > 1 ? $this->relationshipGeneratorServices->joinModelRelationships($relations) : '',
                    maxRecords: array_key_exists('maxRecords', $value) ? $value['maxRecords'] : -1
                );

                /* 
                    Generar Migraciones
                */
                $fields = $value['fields'];
                $relations = $this->searchForRelationships($value['fields']);
                $migrationName = $this->migrationGeneratorServices->generateDVoyagerMigration(
                                                                                table: $value['table'], 
                                                                                keyValueFields: $fields, 
                                                                                relationships: count($relations) > 1 ? $relations : [], 
                                                                                migrationNumber: $migrationNumber++
                                                                            );
                $this->executeMigrateCommand();
                
                /*
                    Generar Breads
                */
                $this->breadGeneratorServices->createBread($value, $key);

                DVoyagerGenerationHistory::create([
                    'bread' => $key,
                    'table' => $value['table'],
                    'migration' => $migrationName,
                    'model' => $className
                ]);
            }
        }
    }

    public function generateClass()
    {
        foreach ($this->breads as $key => $value) {
            $info = $value['info'] ?? [];
            $relations = $this->searchForRelationships($value['fields']);
            //TODO Revisar el tema de los translatables
            $this->classGeneratorServices->generateDVoyagerClass(
                name: $key, 
                table: $value['table'], 
                slugFrom: array_key_exists('slugFrom', $value) ? $value['slugFrom'] : 'title', 
                fieldsTranslatables: array_key_exists('fieldsTranslatable', $value) ? json_encode($value['fieldsTranslatable']) : '',
                fieldsInfo: json_encode($info),
                searchable: isset($value['searchable']) ? json_encode($value['searchable']) : '',
                relationships: count($relations) > 1 ? $this->relationshipGeneratorServices->joinModelRelationships($relations) : '',
                maxRecords: array_key_exists('maxRecords', $value) ? $value['maxRecords'] : -1
            );

        }
    }

    public function generateMigration()
    {
        $migrationNumber = 0;

        foreach ($this->breads as $key => $value) {
            $fields = $value['fields'];
            $relations = $this->searchForRelationships($value['fields']);
            $this->migrationGeneratorServices->generateDVoyagerMigration(
                                                                            table: $value['table'], 
                                                                            keyValueFields: $fields, 
                                                                            relationships: count($relations) > 1 ? $relations : [], 
                                                                            migrationNumber: $migrationNumber++
                                                                        );
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

    public function executeCommonMigrateCommand()
    {
        Artisan::call('migrate');
    }

    public function executeClearConfigCache()
    {
        Artisan::call('config:cache');
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

    private function searchForRelationships($fields)
    {
        $relations = [];
        foreach ($fields as $key => $value) {
            if($value['type'] == 'relation')
            {
                $relations = array_merge($relations, [$key => $value]);
            }
        }

        return $relations;
    }

    public function rollbackGeneration()
    {
        $history = DVoyagerGenerationHistory::orderBy('id', 'desc')->get();
        foreach ($history as $value) {
            $this->rollbackBreads($value->table);
            $this->rollbackMigrations($value->migration, $value->table);
            $this->rollbackModels($value->model);

            $value->delete();
        }
    }

    public function rollbackModels($modelName)
    {
        $modelsPath = config('dvoyager.modelsPath', app_path('Models/DVoyager'));
        unlink($modelsPath.'/'.$modelName);
    }

    public function rollbackMigrations($migrationName, $table)
    {
        $migrationsPath = config('dvoyager.migrationsPath', base_path('database/migrations/DVoyager'));
        $migrationToDeletePath = $migrationsPath.'/'.$migrationName;
        if(file_exists($migrationToDeletePath))
        {
            unlink($migrationToDeletePath);
        }

        DB::table('migrations')->where('migration', str_replace('.php','',$migrationName))->delete();

        Schema::dropIfExists($table);
    }

    public function rollbackBreads($table)
    {
        $dataType = DataType::where('name', $table)->first();

        if($dataType != null)
        {
            if (is_bread_translatable($dataType)) {
                $dataType->deleteAttributeTranslations($dataType->getTranslatableAttributes());
            }

            $res = Voyager::model('DataType')->destroy($dataType->id);

            event(new BreadDeleted($dataType, ''));

            Voyager::model('Permission')->removeFrom($dataType->name);
        }
    }

}