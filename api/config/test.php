<?php
return [
    'id' => 'app-api-tests',
    'components' => [
        'assetManager' => [
            'basePath' => dirname(__FILE__) . '/../web/assets',
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
        'request' => [
            'cookieValidationKey' => 'test',
        ],
    ],
];
