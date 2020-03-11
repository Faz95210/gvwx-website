<?php


use NCmqtt\Components\Application;

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
//defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
defined('STDOUT') or define('STDOUT', fopen('php://stdout', 'w'));
defined('STDERR') or define('STDERR', fopen('php://stderr', 'w'));

require dirname(__FILE__) . '/vendor/autoload.php';
require dirname(__FILE__) . '/vendor/yiisoft/yii2/Yii.php';
require dirname(__FILE__) . '/common/config/bootstrap.php';
require dirname(__FILE__) . '/mqtt/config/bootstrap.php';
require dirname(__FILE__) . '/mqtt/components/Application.php';
require dirname(__FILE__) . '/mqtt/controllers/DeviceController.php';
require dirname(__FILE__) . '/mqtt/assets/phpMQTT.php';

$config = yii\helpers\ArrayHelper::merge(
    require dirname(__FILE__) . '/common/config/main.php',
    require dirname(__FILE__) . '/common/config/main-local.php',
    require dirname(__FILE__) . '/mqtt/config/main.php',
    require dirname(__FILE__) . '/mqtt/config/main-local.php'
);

try {
    $application = new Application($config);

} catch (Exception $e) {
    echo $e;
}
$exitCode = $application->run();
exit($exitCode);
