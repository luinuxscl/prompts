<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tabla de prompts
    |--------------------------------------------------------------------------
    |
    | Nombre de la tabla donde se almacenarán los prompts.
    |
    */
    'table' => 'prompts',

    /*
    |--------------------------------------------------------------------------
    | Profundidad máxima de anidamiento
    |--------------------------------------------------------------------------
    |
    | Límite de anidamiento para evitar bucles infinitos y recursión excesiva.
    |
    */
    'max_nesting_depth' => 5,

    /*
    |--------------------------------------------------------------------------
    | Sintaxis para referencias anidadas
    |--------------------------------------------------------------------------
    |
    | Define cómo se marcan las referencias a otros prompts dentro del contenido.
    | Debe ser un patrón que envuelva el nombre del prompt referenciado.
    |
    */
    'nesting_pattern' => '{{%s}}',
];
