<?php

namespace frontend\controllers;

use api\controllers\ApiBaseController;
use api\models\CompleteCoordinateForm;
use common\models\Address;
use common\models\Coordinate;
use common\models\Device;
use common\models\RawCoordinates;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class AddressController extends ApiBaseController {

    const REQUEST_AUTHENTICATE = 0;

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
                        'roles' => ['@'],
                    ],
                ],
            ]
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

    public function actionCompleteaddress() {
        $params = \Yii::$app->request->post();
        $address = $this->doesAddressAlreadyExists($params);
        if ($address == false) {
            $address = $this->newAddress($params);
        }
        $this->linkAddress($address, $params['coordinate_id']);
    }

    private function newAddress($params) {
        $address = new Address([
            'user_id' => \Yii::$app->user->id,
            'label' => $params['address_label'],
            'address1' => $params['address_address'],
            'city' => $params['address_city'],
            'postcode' => $params['address_cp'],
            'country' => $params['address_country']
        ]);
        $address->save(false);
        return $address;
    }

    private function doesAddressAlreadyExists($post) {
        $address = Address::find()->where(['label' => $post['address_label'],
            'address1' => $post['address_address'],
            'city' => $post['address_city'],
            'postcode' => $post['address_cp'],
            'country' => $post['address_country']
        ])->one();
        return $address == null ? false : $address;
    }


    private function linkAddress($address, $coordinate_id) {
        $coordinate_id = RawCoordinates::find()->where(['id' => $coordinate_id])->one();
        if ($coordinate_id == null)
            return -1;
        $coordinate_id->address_id = $address->id;
        $coordinate_id->update();
    }

}
