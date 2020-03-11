<?php
$params = array_merge(
    require dirname(__FILE__) . '/../../common/config/params.php',
    require dirname(__FILE__) . '/../../common/config/params-local.php',
    require dirname(__FILE__) . '/params.php',
    require dirname(__FILE__) . '/params-local.php'
);

return [
    'id' => 'mqtt',
    'basePath' => dirname(dirname(__FILE__)),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'mqtt\controllers',
    'components' => [
        'auth' => ['class' => 'mqtt\components\Auth'],
        'errorHandler' => ['class' => 'yii\console\ErrorHandler'],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'params' => $params,
];
