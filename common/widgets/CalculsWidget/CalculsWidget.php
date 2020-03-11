<?php

namespace common\widgets\CalculsWidget;

use common\models\Power;
use common\models\ScaleIK;
use common\models\Trip;
use common\models\User;
use common\models\Vehicle;
use yii\base\Widget;
use Yii;

class CalculsWidget extends Widget {
    public $_model;

    public function init() {
        parent::init();
    }

    public function run() {
        CalculsWidgetAssets::register($this->getView());

        $this->view->params['scaleiks'] = $this->getScaleIks();

        return $this->render('_calculs', ['product' => $this->_model]);
    }

    private function getScaleIks() {
        $scaleIks = [];
        $powers = Power::find()->all();
        foreach ($powers as $power) {
            $vehicle = Vehicle::find()
                ->where(['user_id' => Yii::$app->user->identity->getId(), 'power_id' => $power->id])
                ->andWhere(['>', 'status', '0'])
                ->orderBy(['id' => SORT_DESC])
                ->one();
            if ($vehicle != null) {
                $raw_scaleIks = ScaleIK::find()
                    ->where(['power_id' => $power->id])
                    ->andWhere(['>', 'status', '0'])
                    ->all();
                foreach ($raw_scaleIks as $raw_scaleIk) {
                    $formattedScale = [
                        "id" => $raw_scaleIk->id,
                        "vehicle" => $vehicle->brand . " " . $vehicle->model . " " . $vehicle->year,
                        "power" => $power->label,
                        "year" => $raw_scaleIk->year,
                        "raw_data" => $raw_scaleIk
                    ];
                    $scaleIks[] = $formattedScale;
                }
            }
        }
        return $scaleIks;
    }
}