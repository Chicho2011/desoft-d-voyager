<?php

namespace Desoft\DVoyager\Services;

class RelationshipGeneratorServices {

    public function joinModelRelationships($relationshipArray)
    {
        $text = '';
        foreach ($relationshipArray as $key => $value) {
            $keyExploded = explode('_', $key);
            $name = count($keyExploded) > 1 ? snakeToCamel($key) : $key;
            $relationshipType = $value['relationType'];
            $model = $this->generateClassPath($value['relationModel']);
            $relatedField = $value['referenceField'];
            $fieldToUse = $key;

            $result = $this->generateRelationship(name: $name,
                                                        relationshipType: $relationshipType,
                                                        relatedModel: $model,
                                                        relatedField: $relatedField,
                                                        fieldToUse: $fieldToUse
                        );
            
            $text.= $result;
        }

        return $text;
    }

    public function generateRelationship(string $name, 
                                         string $relationshipType, 
                                         string $relatedModel, 
                                         string $relatedField = 'id',
                                         string $fieldToUse
                                         )
    {
        $relationshipString = "
            public function $name()
            {
                return \$this->$relationshipType('$relatedModel', '$fieldToUse', '$relatedField');
            }   
        ";

        return $relationshipString;
    }

    public function generateClassPath(string $posibleClassPath)
    {
        if(class_exists($posibleClassPath))
        {
            return $posibleClassPath;
        }
        return $this->buildClassPath($posibleClassPath);
    }
    
    private function buildClassPath(string $className)
    {
        /*
            Poner esta variable en algún lugar para reutilizarlo, principalmente en el config
        */
        $dvoyagerNamespace = '\App\Models\DVoyager';

        return $dvoyagerNamespace.'\\'.ucfirst($className).'DVoyagerModel';
    }
}