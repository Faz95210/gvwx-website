<?php
$params = array_merge(
    require dirname(__FILE__) . '/../../common/config/params.php',
    require dirname(__FILE__) . '/../../common/config/params-local.php',
    require dirname(__FILE__) . '/params.php',
    require dirname(__FILE__) . '/params-local.php'
);

return [
    'id' => 'app-console',
    'basePath' => dirname(dirname(__FILE__)),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
        ],
    ],
    'components' => [
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
