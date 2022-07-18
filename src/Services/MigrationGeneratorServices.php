<?php

namespace Desoft\DVoyager\Services;

use Carbon\Carbon;
use Desoft\DVoyager\Utils\GeneratorUtilities;
use Exception;

class MigrationGeneratorServices {

    private $migrationsPath;
    private $folderName;

    function __construct(
        private RelationshipGeneratorServices $relationshipGeneratorServices
    )
    {
        $this->folderName = 'DVoyager';
        $this->migrationsPath = base_path('database/migrations');
    }

    /*
        $keyValueFields: Array (la llave pertenece al nombre del campo y el value el tipo)
    */
    public function generateDVoyagerMigration(string $table, array $keyValueFields, array $relationships = [], int $migrationNumber = 0)
    {
        $carbonDate = Carbon::now();

        //Pasar por parametro el índice para mantener el orden de las migraciones
        $date_text = $carbonDate->format('Y_m_d').'_'.$migrationNumber;

        $newMigrationPath = $this->migrationsPath.'/'.$this->folderName.'/'.$date_text.'_create_'.$table.'_table.php';
        $body = $this->generateBody(table: $table, keyValueFields: $keyValueFields, relationships: $relationships);

        if(!is_dir($this->migrationsPath.'/'.$this->folderName))
        {
            mkdir($this->migrationsPath.'/'.$this->folderName);
        }

        if(file_exists($newMigrationPath))
        {
            unlink($newMigrationPath);
        }

        GeneratorUtilities::createFile(path: $newMigrationPath, body: $body);
    }

    private function generateBody(string $table, array $keyValueFields, array $relationships = [])
    {
        $fieldsText = "";

        if(count($keyValueFields) < 0)
        {
            throw(new Exception());
        }

        $fieldsText .= "\$table->id();\n";

        if(!array_key_exists('slug', $keyValueFields))
        {
            $fieldsText .= "\$table->string('slug')->unique();\n";
        }

        foreach ($keyValueFields as $key => $value) {
            if($value['type'] != 'relation')
            {
                $type = $value['type'];
                $isNullable = $value['isNullable'] ? '->nullable(true)' : '->nullable(false)';
                $isUnique = $value['isUnique'] ? '->unique()': '';
                $fieldsText .= "\$table->$type('$key')$isNullable$isUnique;\n";
            }
        }

        $fieldsText .= $this->generateMigrationLineFromRelationships($relationships);

        $fieldsText .= "\$table->timestamps();\n";

        $bodyFromStub = file_get_contents(__DIR__.'/../stubs/migration.stub');

        $bodyFromStub = str_replace('{{ table }}', $table, $bodyFromStub);
        $bodyFromStub = str_replace('{{ fields }}', $fieldsText, $bodyFromStub);

        return $bodyFromStub;
    }

    private function generateMigrationLineFromRelationships($relationShipArray)
    {
        $text = '';
        foreach ($relationShipArray as $key => $value) {
            $fieldName = $key;
            $fieldType = 'unsignedBigInteger';
            $isNullable = $value['isNullable'] ? '->nullable(true)' : '->nullable(false)';
            $isUnique = $value['isUnique'] ? '->unique()': '';
            $relatedTable = app($this->relationshipGeneratorServices->generateClassPath($value['relationModel']))->getTable();
            $relatedField = isset($value['referencesField']) ? $value['referencesField'] : 'id';
            $text .= "
                    \$table->$fieldType('$fieldName')$isNullable$isUnique;
                    \$table->foreign('$fieldName')->references('$relatedField')->on('$relatedTable')->onDelete('CASCADE');
                    ";
        }

        return $text;
    }
}