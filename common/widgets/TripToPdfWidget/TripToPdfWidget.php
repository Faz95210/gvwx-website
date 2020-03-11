<?php

namespace common\widgets\TripToPdfWidget;

use common\models\Trip;
use common\models\User;
use common\widgets\TripsWidget\TripsWidgetAsset;
use yii\base\Widget;

class TripToPdfWidget extends Widget {

    public $filter;
    public $value;

    public function init() {
        if ($this->filter == null) {
            $this->filter = 'year';
            $this->value = date('y');
        }
        parent::init();
    }

    public function run() {
        TripToPdfWidgetAsset::register($this->getView());
        $this->view->params['user'] = User::find()->where(['id' => \Yii::$app->user->id])->one();
        $this->view->params['style'] = file_get_contents(dirname(__FILE__) . "/assets/css/style.css");
        $this->handleFilter();
        $this->view->params['trips'] = Trip::getTripsFilteredByDate(['tag' => $this->filter, 'value' => $this->value])->orderBy(['start_date_time' => SORT_DESC])->all();
        $this->view->params['refund'] = Trip::calculateTotalRefund(['tag' => $this->filter, 'value' => $this->value]);
        return $this->render('_tripToPdf', []);
    }

    private function handleFilter() {
        switch ($this->filter) {
            case "year":
                $this->view->params['from'] = '01/01/' . date('y');
                $this->view->params['to'] = '31/12/' . date('y');
                break;
            case "month":
                $this->view->params['from'] = '01/' . date('m') . '/' . date('Y');
                $this->view->params['to'] = cal_days_in_month(CAL_GREGORIAN, date('m'), date("y")) . '/' . date('m') . '/' . date('y');;
                break;
        }
    }
}