<?php


namespace common\widgets\CarWidget;

use common\models\Vehicle;
use yii\base\Widget;

class CarWidget extends Widget {

    public function init() {
        parent::init();
    }

    public function run() {
        CarWidgetAssets::register($this->getView());
        $cars = Vehicle::find()
            ->where(['user_id' => \Yii::$app->user->id])
            ->andWhere(['>', 'status', '0'])
            ->orderBy(['id' => SORT_ASC])->all();
        $this->view->params['cars'] = $cars;
        return $this->render('_cars', []);
    }
}