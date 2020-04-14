<?php

namespace api\models;

use Yii;
use yii\base\Model;
use common\models\Trip;
use common\models\Address;
use common\models\RawCoordinates;

/**
 * Register form
 */
class NewTripForm extends Model {
    public $user_id;
    public $start_date_time;
    public $stop_date_time;
    public $device_id;
    public $vehicle_id;
    public $duration;
    public $distance;
    public $start_coordinates;
    public $stop_coordinates;
    public $kml_file;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            ['user_id', 'trim'],
            ['user_id', 'required'],
            ['user_id', 'integer'],

            ['device_id', 'trim'],
            ['device_id', 'required'],
            ['device_id', 'integer'],

            ['vehicle_id', 'trim'],
            ['vehicle_id', 'required'],
            ['vehicle_id', 'integer'],

            ['duration', 'trim'],
            ['duration', 'required'],
            ['duration', 'double'],

            ['distance', 'trim'],
            ['distance', 'required'],
            ['distance', 'double'],

            ['kml_file', 'trim'],
            ['kml_file', 'required'],
            ['kml_file', 'string'],

            ['start_date_time', 'trim'],
            ['start_date_time', 'required'],
            ['start_date_time', 'integer'],

            ['stop_date_time', 'trim'],
            ['stop_date_time', 'required'],
            ['stop_date_time', 'integer'],

            ['start_coordinates', 'each', 'rule' => ['double']],
            ['stop_coordinates', 'each', 'rule' => ['double']],
            /*['start_coordinates', 'required'],
            ['start_coordinates', 'array'],

            ['stop_coordinates', 'required'],
            ['stop_coordinates', 'array'],*/
        ];
    }


    /**
     * Add trip
     *
     * @return bool whether the creating new trip was successful
     */
    public function add() {
        if (!$this->validate()) {
            echo "error validate trip\n";
            var_dump($this);

            return null;
        }


        $startAddress = new RawCoordinates();
        $startAddress->user_id = $this->user_id;
        $startAddress->latitude = $this->start_coordinates['lat'];
        $startAddress->longitude = $this->start_coordinates['lng'];
        if (!$startAddress->save()) {
            echo "error save start address\n";
            return null;
        }

        $stopAddress = new RawCoordinates();
        $stopAddress->user_id = $this->user_id;
        $stopAddress->latitude = $this->stop_coordinates['lat'];
        $stopAddress->longitude = $this->stop_coordinates['lng'];
        $stopAddress->save();
        if (!$stopAddress->save()) {
            echo "error save stop address\n";
            return null;
        }

        $trip = new Trip();
        $trip->user_id = $this->user_id;
        $trip->device_id = $this->device_id;
        $trip->vehicle_id = $this->vehicle_id;
        $trip->start_date_time = $this->start_date_time;
        $trip->stop_date_time = $this->stop_date_time;
        $trip->duration = $this->duration;
        $trip->distance = $this->distance;
        $trip->start_coordinate_id = $startAddress->getId();
        $trip->stop_coordinate_id = $stopAddress->getId();
        $trip->kml_file = $this->kml_file;

        if ($trip->save()) {
            return $trip;
        } else {
            echo "error save trip\n";
            return null;
        }
    }
}
