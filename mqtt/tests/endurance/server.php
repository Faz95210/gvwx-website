<?php

use Bluerhinos\phpMQTT;

defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
defined('STDOUT') or define('STDOUT', fopen('php://stdout', 'w'));
defined('STDERR') or define('STDERR', fopen('php://stderr', 'w'));
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../assets/phpMQTT.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../../common/config/main.php',
    require __DIR__ . '/../../../common/config/main-local.php',
    require __DIR__ . '/../../../mqtt/config/params.php',
    require __DIR__ . '/../../../mqtt/config/main-local.php'
);

$need_to_respond = false;
$server = null;
$expectedClients = 1;
$currentNumberOfClients = 0;

run();
function run() {
    global $need_to_respond;
    global $expectedClients;
    global $server;

    $need_to_respond = func_get_args()[0];
    $expectedClient = func_get_args()[1];

    $server = new phpMQTT('localhost', 1883, 'TESTSERVER');
    $server->debug = YII_DEBUG;
    if ($server->connect(true, NULL, Yii::$app->params['username'], Yii::$app->params['password'])) {
        //Link function and qos to message received for topic
        subscribeToRoutes();
        while ($server->proc(true)) {

        }
        $server->close();
    } else {
        echo "Time out!\n";
    }
}

function subscribeToRoutes() {
    global $server;
    $topics['/test/kml'] = array("qos" => 0, "function" => ['', "testKml"]);
    $server->subscribe($topics, 0);
}

function actionNewClient($topic, $message) {

}
