<?php


use NCmqtt\Components\Application;

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
//defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
defined('STDOUT') or define('STDOUT', fopen('php://stdout', 'w'));
defined('STDERR') or define('STDERR', fopen('php://stderr', 'w'));

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/common/config/bootstrap.php';
require __DIR__ . '/mqtt/config/bootstrap.php';
require __DIR__ . '/mqtt/components/Application.php';
require __DIR__ . '/mqtt/controllers/DeviceController.php';
require __DIR__ . '/mqtt/assets/phpMQTT.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/common/config/main.php',
    require __DIR__ . '/common/config/main-local.php',
    require __DIR__ . '/mqtt/config/main.php',
    require __DIR__ . '/mqtt/config/main-local.php'
);

try {
    $application = new Application($config);

} catch (Exception $e) {
    echo $e;
}
$exitCode = $application->run();
exit($exitCode);
