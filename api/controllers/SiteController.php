<?php

namespace api\controllers;

use Yii;
//use yii\web\Controller;
use api\controllers\ApiBaseController;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\web\UploadedFile;
use api\models\RegisterForm;
use api\models\RtTrackingForm;
use api\models\UploadForm;
use api\models\NewTripForm;
use common\models\RtTracking;
use common\models\Device;
use vlaim\fileupload\FileUpload;
use Aws\S3\S3Client;
use ikar\kmlparser\KmlParser;


/**
 * Site controller
 */
class SiteController extends ApiBaseController {
    const REQUEST_AUTHENTICATE = 0;

    private $device;

    // Sample log
    // \Yii::info("saved file", 'logs');

    /**
     * {@inheritdoc}
     */

    public function behaviors() {
        return [
            'authenticator' => [
                //'class' => QueryParamAuth::className(),
                'class' => HttpBasicAuth::className(), 'auth' => [$this, 'auth'],
                'except' => ['register'],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['register'],
                        'allow' => true,
                    ],
                    [
                        'actions' => [
                            'items',
                            'item',
                            'sales',
                            'sale',
                            'mandants',
                            'mandant',
                            'user',
                            'clients',
                            'client'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    /*
    public function actionIndex()
    {
        $params = Yii::$app->request->post();
        $this->authenticate($params); // base controller function for auth
        $data = array("data"=>array("message"=>"success"));
        $this->response(200, $data);

        //return $this->render('index');
    } */

    /**
     * Add a new device
     *
     * @return string
     */
    public function actionRegister() {
        $model = new RegisterForm();
        $params = array("RegisterForm" => Yii::$app->request->post());
        if ($model->load($params) && ($device = $model->register())) {
            $data = array("error" => false,
                "imei" => $device->getImei(),
                "password" => $model->getPassword(),
                "api_key" => $device->getApiKey(),
                "verification_token" => $device->getVerificationToken());
            $this->response(200, $data);
        } else {
            $data = array("error" => true, "message" => "registration failed");
            $this->response(403, $data);
        }
    }

}
