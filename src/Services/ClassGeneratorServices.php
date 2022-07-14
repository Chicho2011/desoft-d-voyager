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

    public function generateDVoyagerClass(string $name, string $table, string $slugFrom = 'title', string $fieldsTranslatables = '[]', string $fieldsInfo = '[]')
    {
        $capitalizeName = ucfirst($name);
        $newClassPath = $this->modelsPath.'/'.$this->folderName.'/'.$capitalizeName.'DVoyagerModel.php';
        $body = $this->generateClassBody(capitalizeName: $capitalizeName.'DVoyagerModel', table: $table, slugFrom: $slugFrom, fieldsTranslatables: $fieldsTranslatables, path: $newClassPath, fieldsInfo: $fieldsInfo);

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
                                       string $fieldsTranslatables = '[]', 
                                       string $path,
                                       string $fieldsInfo = '[]',
                                       )
    {
        $namespace = "App\Models\\".$this->folderName;

        $bodyFromStubs = file_get_contents(__DIR__.'/../stubs/model.stub');

        $namespaceReplaces = str_replace('{{ namespace }}', $namespace, $bodyFromStubs);
        $classReplaces = str_replace('{{ className }}', $capitalizeName, $namespaceReplaces);
        $tableReplaces = str_replace('{{ table }}', $table, $classReplaces);
        $slugFromReplaces = str_replace('{{ slugFrom }}', $slugFrom, $tableReplaces);
        $fieldsTranslatablesReplaces = str_replace('{{ translatable }}', $fieldsTranslatables, $slugFromReplaces);
        $fieldsInfoReplaces = str_replace('{{ fieldsInfo }}', $fieldsInfo, $fieldsTranslatablesReplaces);

        return $fieldsInfoReplaces;
    }
}