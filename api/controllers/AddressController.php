<?php

namespace api\controllers;

use api\models\CompleteCoordinateForm;
use common\models\Device;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class AddressController extends ApiBaseController {

    const REQUEST_AUTHENTICATE = 0;

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
                            'completeaddress'
                        ],
                        'allow' => true,
//                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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

    public function auth($imei, $password) {
        //$user = User::find()->where(['email' => $phonenumber])->one();
        $this->device = Device::findByImei($imei);
        if ($this->device && $this->device->validatePassword($password)) {
            return $this->device;
        }
        return null;
        //$device = User::findByEmail($phonenumber);
        //return $device->validatePassword($password);
        // return \common\models\User::findOne
        // ([
        //     'email' => $phonenumber,
        //     'password' => $password,
        //  ]);
    }

    public function beforeAction($action) {
        // ...set `$this->enableCsrfValidation` here based on some conditions...
        // call parent method that will check CSRF if such property is `true`.
        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }

    public function actionCompleteaddress() {
        if (self::REQUEST_AUTHENTICATE) {
            $params = Yii::$app->request->post();
            $this->authenticate($params, $this->device->getApiKey()); // base controller function for auth
        }
        if (Yii::$app->request->isPost) {
            $model = new CompleteCoordinateForm();
            $params = array("CompleteCoordinateForm" => Yii::$app->request->post());
            if ($model->load($params) && ($error = $model->update()) > 0) {
                $data = array("error" => $error);
                $this->response(202, $data);
            } else {
                switch ($error) {
                    case -1:
                        $message = "Wrong parameters. Expecting coordinate_id and address_id";
                        break;
                    case -2:
                        $message = "Couldn't find coordinate_id in the db";
                        break;
                }
                $data = array("error" => true, "message" => $message);
                $this->response(400, $data);
            }
            return;
        }
        $data = array("error" => "Only accept POST requests");
        $this->response(405, $data);
    }
}