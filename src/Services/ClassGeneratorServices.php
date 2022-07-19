<?php

namespace Desoft\DVoyager\Services;

use Desoft\DVoyager\Utils\GeneratorUtilities;

class ClassGeneratorServices {

    private $modelsPath;
    private $folderName;

    function __construct()
    {
        $this->folderName = 'DVoyager';
        $this->modelsPath = base_path('app/Models');
    }

    public function generateDVoyagerClass(string $name, 
                                          string $table, 
                                          string $slugFrom = 'title', 
                                          string $fieldsTranslatables = '', 
                                          string $fieldsInfo = '[]',
                                          string $searchable = '',
                                          string $relationships = '',
                                          int $maxRecords = -1
                                         )
    {
        $capitalizeName = ucfirst($name);
        $newClassPath = $this->modelsPath.'/'.$this->folderName.'/'.$capitalizeName.'DVoyagerModel.php';
        $body = $this->generateClassBody(capitalizeName: $capitalizeName.'DVoyagerModel', 
                                         table: $table, 
                                         slugFrom: $slugFrom, 
                                         fieldsTranslatables: $fieldsTranslatables, 
                                         fieldsInfo: $fieldsInfo,
                                         searchable: $searchable,
                                         relationships: $relationships,
                                         maxRecords: $maxRecords
                                        );

        if(!is_dir($this->modelsPath.'/'.$this->folderName))
        {
            mkdir($this->modelsPath.'/'.$this->folderName);
        }

        if(file_exists($newClassPath))
        {
            unlink($newClassPath);
        }

        GeneratorUtilities::createFile(path: $newClassPath, body: $body);
    }

    private function generateClassBody(string $capitalizeName, 
                                       string $table, 
                                       string $slugFrom = 'title', 
                                       string $fieldsTranslatables = '', 
                                       string $fieldsInfo = '',
                                       string $searchable = '',
                                       string $relationships = '',
                                       int $maxRecords = -1
                                       )
    {
        $namespace = "App\Models\\".$this->folderName;

        $bodyFromStubs = file_get_contents(__DIR__.'/../stubs/model.stub');

        $bodyFromStubs = str_replace('{{ namespace }}', $namespace, $bodyFromStubs);
        $bodyFromStubs = str_replace('{{ className }}', $capitalizeName, $bodyFromStubs);
        $bodyFromStubs = str_replace('{{ table }}', $table, $bodyFromStubs);
        $bodyFromStubs = str_replace('{{ slugFrom }}', $slugFrom, $bodyFromStubs);
        $bodyFromStubs = str_replace('{{ translatable }}', $fieldsTranslatables != '' ? $fieldsTranslatables : json_encode([]), $bodyFromStubs);
        $bodyFromStubs = str_replace('{{ maxRecords }}', $maxRecords, $bodyFromStubs);
        $bodyFromStubs = str_replace('{{ fieldsInfo }}', $fieldsInfo, $bodyFromStubs);
        if($searchable != '')
        {
            $bodyFromStubs = str_replace('{{ traits }}', 'use \\Desoft\\DVoyager\\Traits\\SearchChanges;', $bodyFromStubs);
        }
        else{
            $bodyFromStubs = str_replace('{{ traits }}', '', $bodyFromStubs);
        }
        $bodyFromStubs = str_replace('{{ searchable }}', $searchable != '' ? $searchable : json_encode([]), $bodyFromStubs);
        $bodyFromStubs = str_replace('{{ relationships }}', $relationships, $bodyFromStubs);

        return $bodyFromStubs;
    }
}