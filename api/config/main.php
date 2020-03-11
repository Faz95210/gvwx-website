<?php
$params = array_merge(
    require dirname(__FILE__) . '/../../common/config/params.php',
    require dirname(__FILE__) . '/../../common/config/params-local.php',
    require dirname(__FILE__) . '/params.php',
    require dirname(__FILE__) . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(dirname(__FILE__)),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-api',
        ],
        'user' => [
            'identityClass' => 'api\models\Device',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null,
            //'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
        ],
        //'session' => [
        // this is the name of the session cookie used for login on the api
        //'name' => 'advanced-api',
        //],
        'log' => [
            //'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                /*[
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                ], */
                [
                    'class' => 'yii\log\FileTarget',
                    'categories' => ['logs'],
                    'exportInterval' => 1,
                    'logFile' => '@app/runtime/logs/my.log',
                    'levels' => ['error', 'warning', 'info'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
//        'urlManager' => [
//            'enablePrettyUrl' => true,
////            'enableStrictParsing' => true,
//            'showScriptName' => false,
//            'rules' => [
//                ['class' => 'yii\rest\UrlRule', 'controller' => 'widget'],
//            ],
//        ]
    ],
    'params' => $params,
];
