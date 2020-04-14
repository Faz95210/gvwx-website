<?php


namespace api\models;


use common\models\Coordinate;
use yii\base\Model;

class CompleteCoordinateForm extends Model {
    public $address_id;
    public $coordinate_id;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['address_id', 'coordinate_id'], 'required'],
            [['address_id', 'coordinate_id'], 'number'],
        ];
    }

    public function update() {
        if (!$this->coordinate_id || !$this->address_id)
            return -1;
        $coordinate = Coordinate::findOne(["id" => $this->coordinate_id]);
        if ($coordinate === null) {
            return -2;
        }
        return $coordinate->setAddressId($this->address_id);
    }

}