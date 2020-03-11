<?php

namespace common\widgets\IncompleteAddressWidget;

use common\models\Address;
use common\models\Coordinate;
use Yii;
use yii\base\Widget;

class IncompleteAddressWidget extends Widget {
    public $coordinate_id;
    public $closestAddresses;
    public $light;

    public function init() {
        parent::init();
    }

    private function getAddressDetail($latlng) {
        $url = Yii::$app->params['reverseGeocodeAddress'];

        $url = str_replace('##LAT##', $latlng['lat'], $url);
        $url = str_replace('##LNG##', $latlng['lng'], $url);
        $url = str_replace('##EMAIL##', Yii::$app->params['adminEmail'], $url);
//        $res = ['error'=>'error'];
        $res = json_decode(file_get_contents($url, true));
        return $res;
    }

    public function run() {
        // Register AssetBundle
        IncompleteAddressWidgetAssets::register($this->getView());

        $this->view->params['coordinate_id'] = $this->coordinate_id;
        $this->view->params['light'] = true;

        $coordinateModel = Coordinate::find()->where(['id' => $this->coordinate_id])->one();
        if ($coordinateModel == null) {
            return;
        }
        $this->view->params['closest_addresses'] = $coordinateModel->closest_complete_addresses;
        $this->view->params['coordinate_raw_address'] = "";
        $this->view->params['coordinate_address'] = "";
        $this->view->params['coordinate_city'] = "";
        $this->view->params['coordinate_CP'] = "";
        $this->view->params['coordinate_country'] = "";
        $addressResponse = $this->getAddressDetail(['lat' => $coordinateModel->latitude, 'lng' => $coordinateModel->longitude]);

        if (!isset($addressResponse->error)) {
            $address = $addressResponse->address;
            $this->view->params['coordinate_address'] = "";
            if (isset($address->house_number)) {
                $this->view->params['coordinate_address'] = $address->house_number . ' ';
            }
            if (isset($address->road)) {
                $this->view->params['coordinate_address'] .= $address->road . ',<br>';
            }
//
//            if (isset($address->postcode)) {
//                $formatted_address .= $address->postcode . ' ';
//            }
//
            if (isset($address->city)) {
                $this->view->params['coordinate_city'] = $address->city . ' ';
            } else if (isset($address->county)) {
                $this->view->params['coordinate_city'] = $address->county . ' ';
            } else if (isset($address->village)) {
                $this->view->params['coordinate_city'] = $address->village . ' ';
            }
            $this->view->params['coordinate_raw_address'] = $address;
            $this->view->params['coordinate_CP'] = $address->postcode;
            $this->view->params['coordinate_country'] = $address->country;
        }
        return $this->render('_incompleteAddress');
    }
}