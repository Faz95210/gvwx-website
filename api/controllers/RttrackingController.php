<?php


namespace api\controllers;


use common\models\Device;
use common\models\RtTracking;
use dosamigos\leaflet\types\LatLng;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;

class RttrackingController extends ApiBaseController {

    private $device;

    public function behaviors() {
        return [
//            'authenticator' => [
//                //'class' => QueryParamAuth::className(),
//                'class' => HttpBasicAuth::className(), 'auth' => [$this, 'auth'],
//                'except' => ['register'],
//            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'getlist'
                        ],
                        'allow' => true,
//                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionGetlist() {
        if (Yii::$app->request->isGet) {
            //An RtTrackingId is given so gives only RtTracking more recent than it for user
            if (($tracking_id = Yii::$app->request->get("RtTrackingId"))) {
                $rtTrackings = RtTracking::find()
                    ->select("rtTracking.*")
                    ->where(["device_id" => Yii::$app->request->get('DeviceId') == "" ? Yii::$app->user->identity->getId() : Yii::$app->request->get('DeviceId')])
                    ->andWhere([">", "rtTracking.id", $tracking_id])
                    ->orderBy('id')
                    ->all();
            } //No RtTrackingId is given so return all RtTracking for user
            else {
                $rtTrackings = RtTracking::find()
                    ->select("rtTracking.*")
//                    ->leftJoin('device', 'device.id = rtTracking.device_id')
                    ->where(["device_id" => Yii::$app->request->get('DeviceId') == "" ? Yii::$app->user->identity->getId() : Yii::$app->request->get('DeviceId')])
                    ->orderBy('id')
                    ->all();
            }
            $return_value = array();
            foreach ($rtTrackings as $tracking) {
                $return_value[] = [
                    'id' => $tracking->id,
                    'device_id' => $tracking->device_id,
                    'lat' => $tracking->lat,
                    'lng' => $tracking->lng,
                    'timestamp' => $tracking->updated_at
                ];
            }
            $data = array("error" => "false", "data" => $return_value);
            $this->response(200, $data);

        } else {
            $data = array("error" => "Only accept GET requests");
            $this->response(405, $data);
        }
    }

    public function auth($imei, $password) {
        //$user = User::find()->where(['email' => $phonenumber])->one();
        $this->device = Device::findByImei($imei);
        if ($this->device && $this->device->validatePassword($password)) {
            return $this->device;
        }
        return null;
    }

    public function beforeAction($action) {
        // ...set `$this->enableCsrfValidation` here based on some conditions...
        // call parent method that will check CSRF if such property is `true`.
        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }
}