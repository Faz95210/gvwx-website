<?php
return [
    'id' => 'app-common-tests',
    'basePath' => dirname(dirname(__FILE__)),
    'components' => [
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\User',
        ],
    ],
];
