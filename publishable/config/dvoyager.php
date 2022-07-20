<?php
/*
    Opciones para los breads
    'nombreEnCamelCaseDelRecurso' => [
        'type' => Tipo del campo en la Base de Datos - string,
        'slugFrom' => Campo a partir se forma el slug. Tiene que existir el campo. - string
        'single_name' => Nombre en singular que se muestra en los BREADs - string,
        'plural_name' => Nombre en plural que se muestra en los BREADs - string,
        'maxRecords' => total de elementos máximos a guardar. Si no se incluye se coge -1. - integer
        'info' => [ \\Opcional
            'idioma' => [
                'nombre_del_campo' => Texto que se quiera mostrar. - string
            ]
        ],
        'fields' => [
            'nombre_en_snake_case_del_campo' => [
                'type' => tipo de la base de datos. Si es una relación se pone tipo 'relation' - string,
                'voyager_type' => Tipo de campo de voyager. Si no se introduce coge text - string,
                'displayName' => nombre que se muestra del campo en los BREADs. - string,
                'isNullable' => Si es requerido - boolean,
                'isUnique' => Si es único - boolean,
                'validation' => [
                    'rule' => validaciones en formato: 'required|max:20' - string
                ],
                'relationType' => Solo se utiliza si el type es relation. Tipo de la relación. Actualmente solo belongsTo. - string,
                'referenceField' => Solo se utiliza si el type es relation. Campo por el que se va a realizar la relación. Normalmente id. - string,
                'relationModel' => Solo se utiliza si el type es relation. 
                                   Namespace del Modelo al se apunta en la relación. 
                                   Ej: App\Models\User. Si se utiliza un modelo que es generado por esta vía se puede poner solo el nombre del recurso.
                                   Si se utiliza de la última forma, tiene que haberse generado con antelación. - string,
                'fieldToSee' => Solo se utiliza si el type es relation. Campo perteneciente a la otra entidad por el que se va a ver. Si no se incluye se coge id. - string
            ]
        ]
    ]
*/
return [
    'act_date_format' => 'd.m.Y',
    'breads' => [
        'news' => [
            'table' => 'news',
            'slugFrom' => 'title',
            'fields' => [
                'title' => [
                    'type' => 'string',
                    'isNullable' => false,
                    'isUnique' => false
                ],
                'description' => [
                    'type' => 'text',
                    'isNullable' => false,
                    'isUnique' => false
                ],
                'from' => [
                    'type' => 'string',
                    'isNullable' => false,
                    'isUnique' => false
                ]
            ]
        ],
        'event' => [
            'table' => 'events',
            'slugFrom' => 'title',
            'fields' => [
                'title' => [
                    'type' => 'string',
                    'isNullable' => false,
                    'isUnique' => false
                ],
                'description' => [
                    'type' => 'text',
                    'isNullable' => false,
                    'isUnique' => false
                ]
            ]
        ],
    ]
];