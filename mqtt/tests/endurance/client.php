<?php

use Bluerhinos\phpMQTT;

defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
defined('STDOUT') or define('STDOUT', fopen('php://stdout', 'w'));
defined('STDERR') or define('STDERR', fopen('php://stderr', 'w'));
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require dirname(__FILE__) . '/../../../vendor/autoload.php';
require dirname(__FILE__) . '/../../../vendor/yiisoft/yii2/Yii.php';
require dirname(__FILE__) . '/../../assets/phpMQTT.php';

$config = yii\helpers\ArrayHelper::merge(
    require dirname(__FILE__) . '/../../../common/config/main.php',
    require dirname(__FILE__) . '/../../../common/config/main-local.php',
    require dirname(__FILE__) . '/../../../mqtt/config/params.php',
    require dirname(__FILE__) . '/../../../mqtt/config/main-local.php'
);

$frequence = 10;

run();

function run() {
    global $frequence;

    $frequence = func_get_args()[1];

    $client = new phpMQTT('localhost', 1883, 'Client' . func_get_args()[0]);
    $client->publish("/test/login", func_get_args()[0], 0);

}

