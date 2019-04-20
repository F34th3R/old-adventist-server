<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS
    |--------------------------------------------------------------------------
    |
    | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
    | to accept any value.
    |
    */
   
    'supportsCredentials' => false,
    'allowedOrigins' => ['*'],
    'allowedOriginsPatterns' => [],
    'allowedHeaders' => ['*'],
    // 'allowedHeaders' => ['Content-Type, X-Auth-Token, Origin, Authorization'],
    'allowedMethods' => ['*'],
    // 'allowedMethods' => ['POST, GET, OPTIONS, PUT, DELETE'],
    'exposedHeaders' => [],
    'maxAge' => 0,

];
