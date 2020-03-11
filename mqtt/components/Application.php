<?php

namespace NCmqtt\Components;

use Bluerhinos\phpMQTT;
use NCmqtt\Controllers\DeviceController;
use Yii;


class Application extends \yii\base\Application {
    public $server;

    public function __construct($config = []) {
        parent::__construct($config);
    }


    public function run() {
        $this->server = new phpMQTT(Yii::$app->params['remote'], Yii::$app->params['port'], Yii::$app->params['clientId']);
        $this->server->debug = YII_DEBUG;
        if ($this->server->connect(true, NULL, Yii::$app->params['username'], Yii::$app->params['password'])) {
            //Link function and qos to message received for topic
            $this->subscribeToRoutes();


            echo "Subscribed" . PHP_EOL;
            $i = 0;
            while ($this->server->proc(true)) {
            }
            echo "FINISHED" . PHP_EOL;
            $this->server->close();
        } else {
            echo "Time out!\n";
        }
    }

    public function handleRequest($_) {
    }

    private function subscribeToRoutes() {
        //TODO Accept static functions as routes callbacks
        $controller = new DeviceController($this->server);
        $topics['/register'] = array("qos" => 0, "function" => [$controller, "actionRegister"]);
        $topics['/awake/+/+'] = array("qos" => 0, "function" => [$controller, "actionAwake"]);
        $topics['/rttracking/+/+'] = array("qos" => 0, "function" => [$controller, "actionRtTracking"]);
        $topics['/clearrttracking/+/+'] = array("qos" => 0, "function" => [$controller, "actionClearRtTracking"]);
        $topics['/tripfile/+/+/+/+/+'] = array("qos" => 0, "function" => [$controller, "actionSendTrip"]);

        $this->server->subscribe($topics, 0);
    }
}
