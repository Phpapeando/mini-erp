<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Serviço de CEP
    |--------------------------------------------------------------------------
    |
    | Aqui você pode definir qual serviço de CEP será utilizado.
    |
    | Serviços disponíveis: 'viacep', 'postmon', 'hybrid'
    |
    */
    'service' => env('CEP_SERVICE', 'viacep'),

    /*
    |--------------------------------------------------------------------------
    | Mapeamento de Serviços
    |--------------------------------------------------------------------------
    |
    | Mapeamento entre os nomes dos serviços e suas respectivas classes.
    |
    */
    'services' => [
        'viacep' => \App\Services\ViaCepService::class,
        'postmon' => \App\Services\PostmonCepService::class,
        'hybrid' => \App\Services\HybridCepService::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações de Timeout
    |--------------------------------------------------------------------------
    |
    | Tempo limite para requisições aos serviços de CEP (em segundos).
    |
    */
    'timeout' => env('CEP_TIMEOUT', 10),

];
