<?php

return yii\helpers\ArrayHelper::merge(
    require dirname(__FILE__) . '/main.php',
    require dirname(__FILE__) . '/main-local.php',
    require dirname(__FILE__) . '/test.php',
    require dirname(__FILE__) . '/test-local.php',
    [
        'components' => [
            'request' => [
                // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
                'cookieValidationKey' => '',
            ],
        ],
    ]
);
