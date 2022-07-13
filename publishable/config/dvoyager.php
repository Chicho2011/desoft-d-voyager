<?php

return [
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