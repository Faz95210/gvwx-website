<?php

namespace NCmqtt\Controllers;

use api\models\NewTripForm;
use api\models\RegisterForm;
use api\models\RtTrackingForm;
use common\models\Device;
use common\models\RtTracking;
use ikar\tripparser\TripParser;
use Yii;
use yii\base\ErrorException;

class DeviceController {

    private $server;
    private $device;

    public function __construct(&$server) {
        $this->server = $server;
        $this->device = null;
    }

    private function auth($imei, $password) {
        //$user = User::find()->where(['email' => $phonenumber])->one();
        $this->device = Device::findByImei($imei);
        if ($this->device && $this->device->validatePassword($password)) {
            return $this->device;
        }

        return null;
    }

    private function errorAuthentication($imei) {
//        $data = array("error"=>true, "message"=>"Error authentication");
        // $this->server->publish('/error/'.$imei, json_encode($data));
        $this->server->publish('/error/' . $imei, "1000"); // Error authentication
    }

    private function signedint32($value) {
        $i = (int)$value;
        if (PHP_INT_SIZE > 4)   // e.g. php 64bit
            if ($i & 0x80000000) // is negative
                return $i - 0x100000000;
        return $i;
    }

    public function actionRtTracking($topic, $msg) {
        $explodedTopic = explode('/', $topic);
        $imei = $explodedTopic[2];
        $password = $explodedTopic[3];

        $this->auth($imei, $password);
        if ($this->device == null) {
            $this->errorAuthentication($imei);
            return;
        }

        try {
            $bin = unpack("Nlng/Nlat", base64_decode($msg));
            $lng = $this->signedint32($bin["lng"]) / 1000000;
            $lat = $this->signedint32($bin["lat"]) / 1000000;
        } catch (ErrorException $ex) {
            echo "Error : " . $ex->getName() . " in RtTracking while converting $msg to latlng" . PHP_EOL;
            echo $ex . PHP_EOL;
            return;
        }

        if ($this->device != null) {
            $model = new RtTrackingForm();
            $model->lat = $lat;
            $model->lng = $lng;
            if ($tracking = $model->save($this->device->id)) {
                echo "RtTracking Saved" . PHP_EOL;
            } else {
                echo "Couldn't save rt tracking" . PHP_EOL;
            }
        } else {
            echo "wrong credentials" . PHP_EOL;
        }
    }

    public function actionRegister($topic, $msg) {
        $imei = $msg;

        // TODO, integrate verification with SIM Card provider


        $model = new RegisterForm();
        $model->imei = $imei;

        if (($device = $model->register())) {
            $data = array("error" => false,
                "imei" => $device->getImei(),
                "password" => $model->getPassword(),
                "api_key" => $device->getApiKey(),
                "verification_token" => $device->getVerificationToken());
            echo "register ok" . PHP_EOL;
            $this->server->publish('/register/' . $imei, json_encode($data));
        } else {
            $data = array("error" => true, "message" => "registration failed");
            echo "register ko" . PHP_EOL;
            $this->server->publish('/register/' . $imei, json_encode($data));
        }
    }

    public function actionAwake($topic, $_) {
        $explodedTopic = explode('/', $topic);
        $imei = $explodedTopic[2];
        $password = $explodedTopic[3];

        $this->auth($imei, $password);
        if ($this->device == null) {
            $this->errorAuthentication($imei);
            return;
        }

        $this->device->updateAwake();
        if ($this->device->getUserId() != 0) {
            $data = array("error" => false, "paired" => true);
            $this->server->publish('/awake/' . $imei, json_encode($data));
        } else {
            $data = array("error" => false);
            $this->server->publish('/awake/' . $imei, json_encode($data));
        }
    }

    public function actionClearRtTracking($topic, $_) {
        $explodedTopic = explode('/', $topic);
        $imei = $explodedTopic[2];
        $password = $explodedTopic[3];

        $this->auth($imei, $password);
        if ($this->device == null) {
            $this->errorAuthentication($imei);
            return;
        }

        if (!$this->device->isActive()) {
            $data = array("error" => true, "message" => "device not activated");
            $this->server->publish('/clearrttracking/' . $imei, json_encode($data));
            return;
        }

        RtTracking::deleteAll(['device_id' => $this->device->getId()]);

        $data = array("error" => false);
        $this->server->publish('/clearrttracking/' . $imei, json_encode($data));
    }

    private function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") rrmdir($dir . "/" . $object); else unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    public function actionSendTrip($topic, $msg) {
        echo "SendTrip: " . $topic . "\n";
        $explodedTopic = explode('/', $topic);
        $imei = $explodedTopic[2];
        $password = $explodedTopic[3];
        $part = $explodedTopic[4];
        $nbParts = $explodedTopic[5];
        $md5 = $explodedTopic[6];

        $this->auth($imei, $password);
        if ($this->device == null) {
            $this->errorAuthentication($imei);
            return;
        }

        echo "SendTrip: auth OK\n";

        // Store separated parts on temp upload folder on server
        $path = Yii::getAlias('@api/runtime/upload/temp/' . $this->device->getId() . '/' . $md5 . '/');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $filename = "trip_" . $part . ".bin";
        file_put_contents($path . $filename, $msg);

        echo "SendTrip: save temporaty file: " . $path . $filename . "\n";

        if ($part + 1 == $nbParts) {
            echo "SendTrip: last part done\n";
            // Concatenate complete file
            $fileContent = "";
            for ($i = 0; $i < $nbParts; $i++) {
                $partFilename = $path . "trip_" . $i . ".bin";
                if (!file_exists($partFilename)) {
                    echo "Part of the file does not exists: " . $partFilename . PHP_EOL;
                    echo "Return error" . PHP_EOL;

                    //$this->rrmdir($path);
                    $this->server->publish('/tripfile/' . $imei . '/' . $md5, "580");
                    return;
                }
                $fileContent .= file_get_contents($partFilename);
            }

            echo "SendTrip: concat file done\n";
            // Verify MD5
            $md5Calculated = md5($fileContent);
            if ($md5Calculated != $md5) {
                echo "MD5 don't match: " . $md5Calculated . " <> " . $md5 . PHP_EOL;
                echo "Return error" . PHP_EOL;

                //$this->rrmdir($path);
                $this->server->publish('/tripfile/' . $imei . '/' . $md5, "580");
                return;
            }

            echo "SendTrip: file MD5: " . $md5Calculated . " - " . $md5 . "\n";

            try {
                // Extract data from the file
                $tripParser = new TripParser($fileContent);
                $tripParser->parse();
                if (!$tripParser->isValid()) {
                    echo "File content is not a valid trip " . PHP_EOL;
                    echo "Return error" . PHP_EOL;
                    echo "Delete file" . PHP_EOL;

                    //$this->rrmdir($path);
                    $this->server->publish('/tripfile/' . $imei . '/' . $md5, "500");
                    return;
                }
                echo "SendTrip: parser bin OK\n";
                $tripParser->process();
                $tripParser->generateKml();
                echo "SendTrip: KML generation OK\n";
                $tripParser->saveKmlToS3("kml/" . $this->device->getId() . "/" . time() . "/",
                    [
                        'version' => 'latest',
                        'region' => Yii::$app->params['s3.region'],
                        'credentials' => [
                            'key' => Yii::$app->params['s3.key'],
                            'secret' => Yii::$app->params['s3.secret'],
                        ],
                        'bucket' => Yii::$app->params['s3.bucket'],
                    ]);
            } catch (\Throwable $e) {
                echo "Error while parsing the trip file\n";
                var_dump($e);

                //$this->rrmdir($path);
                $this->server->publish('/tripfile/' . $imei . '/' . $md5, "580");
                return;
            }


            echo "SendTrip: Saved to S3 OK\n";
            echo "Trip Distance: " . $tripParser->getDistance() . "\n";


            $model = new NewTripForm();

            $params = array("NewTripForm" => array(
                'user_id' => $this->device->getUserId(),
                'device_id' => $this->device->getId(),
                'vehicle_id' => $this->device->getVehicleId(),
                'duration' => $tripParser->getDuration(),
                'distance' => $tripParser->getDistance(),
                'start_date_time' => $tripParser->getStartDateTime(),
                'stop_date_time' => $tripParser->getStopDateTime(),
                'start_coordinates' => $tripParser->getStartCoordinates(),
                'stop_coordinates' => $tripParser->getStopCoordinates(),
                'kml_file' => $tripParser->getPath()
            ));

            $load = $model->load($params);
            $add = $model->add();

            // var_dump($load);
            // var_dump($add);

            if ($load && ($device = $add)) {
                $this->rrmdir($path);
                $this->server->publish('/tripfile/' . $imei . '/' . $md5, "500");
            } else {
                echo "Error can't save trip\n";

                //$this->rrmdir($path);
                $this->server->publish('/tripfile/' . $imei . '/' . $md5, "580");
                return;
            }
        }
    }

}