<?php
//This set will insure (new immusen\mqtt\Application($config);) running in portal shell ./mqtt-server.php
Yii::setAlias('@mqtt', dirname(dirname(__DIR__)) . '/mqtt');