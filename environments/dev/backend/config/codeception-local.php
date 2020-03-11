<?php

return yii\helpers\ArrayHelper::merge(
    require dirname(dirname(dirname(__FILE__))) . '/common/config/codeception-local.php',
    require dirname(__FILE__) . '/main.php',
    require dirname(__FILE__) . '/main-local.php',
    require dirname(__FILE__) . '/test.php',
    require dirname(__FILE__) . '/test-local.php',
    [
    ]
);
