<?php

// NOTE: Make sure this file is not accessible when deployed to production
if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    die('You are not allowed to access this file.');
}

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

require dirname(__FILE__) . '/../../vendor/autoload.php';
require dirname(__FILE__) . '/../../vendor/yiisoft/yii2/Yii.php';
require dirname(__FILE__) . '/../../common/config/bootstrap.php';
require dirname(__FILE__) . '/../config/bootstrap.php';


$config = yii\helpers\ArrayHelper::merge(
    require dirname(__FILE__) . '/../../common/config/main.php',
    require dirname(__FILE__) . '/../../common/config/main-local.php',
    require dirname(__FILE__) . '/../../common/config/test.php',
    require dirname(__FILE__) . '/../../common/config/test-local.php',
    require dirname(__FILE__) . '/../config/main.php',
    require dirname(__FILE__) . '/../config/main-local.php',
    require dirname(__FILE__) . '/../config/test.php',
    require dirname(__FILE__) . '/../config/test-local.php'
);

(new yii\web\Application($config))->run();
