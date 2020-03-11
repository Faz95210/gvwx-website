<?php

namespace api\models;

use common\models\RtTracking;
use yii\base\Model;

/**
 * Register form
 */
class RtTrackingForm extends Model {
    public $lng;
    public $lat;
    private $deviceId;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            ['lat', 'trim'],
            ['lat', 'required'],
            ['lat', 'number'],

            ['lng', 'trim'],
            ['lng', 'required'],
            ['lng', 'number'],
        ];
    }


    /**
     * save
     *
     * @return bool whether the creating new device was successful
     */
    public function save($deviceId) {
        if (!$this->validate()) {
            return null;
        }

        $tracking = new RtTracking();
        $tracking->lng = $this->lng;
        $tracking->lat = $this->lat;
        $tracking->device_id = $deviceId;

        if ($tracking->save()) {
            return $tracking;
        } else {
            return null;
        }

    }
}
