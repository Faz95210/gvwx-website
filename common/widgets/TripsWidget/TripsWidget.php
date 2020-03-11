<?php

namespace common\widgets\TripsWidget;

use common\models\ScaleIK;
use common\models\Trip;
use yii\base\Widget;
use Yii;

class TripsWidget extends Widget {
    public $_mode = 'standard'; // standard for trips table, light for home page style, single for trip desc
    public $_singleTripId = null;
    private $_incompletes = false;
    public $_filters = null;

    public function init() {
        parent::init();
        if (\Yii::$app->request->get('incompletes')) {
            $this->_incompletes = true;
            $this->view->params['incomplete'] = true;
        }
    }

    private function setFilters() {
        $filter = [];
        if (($raw_filter = Yii::$app->request->get('filter')) || ($raw_filter = $this->_filters)) {
            if ($raw_filter === "currentYear") {
                $filter['tag'] = 'year';
                $filter['value'] = date('Y');
            } else if ($raw_filter === "currentMonth") {
                $filter['tag'] = 'month';
                $filter['value'] = date('m');
            } else if ($raw_filter === "period") {
                $filter['tag'] = 'period';
                $filter['value']['from'] = $this->monthToEnglish(Yii::$app->request->get('from'));
                $filter['value']['to'] = $this->monthToEnglish(Yii::$app->request->get('to'));
            }
        }
        return $filter;
    }

    public function run() {
        // Register AssetBundle
        TripsWidgetAsset::register($this->getView());

        $filter = $this->setFilters();


        $limit = (Yii::$app->request->get('limit') ? Yii::$app->request->get('limit') : 10);
        $count = Trip::find()->where(["user_id" => \Yii::$app->user->id])->count();
        $page = (Yii::$app->request->get('page') ? Yii::$app->request->get('page') : 1);
        if ($limit >= $count) {
            $page = 1;
        }
        if ($this->_singleTripId) {
            $this->view->params['trips'] = $this->getTrip();
            $this->view->params['mode'] = 'single';
        } else {
            $this->view->params['trips'] = $this->getTrips($filter, $limit, $page);
            $this->view->params['mode'] = $this->_mode;
        }
        $this->view->params['count'] = count(Trip::getIncompleteTrips());
        $this->view->params['limit'] = $limit;
        $this->view->params['page'] = $limit > $count ? 1 : $page;
        $this->view->params['btoa'] = base64_encode(Yii::$app->user->getIdentity()->getId() . ':' . Yii::$app->user->getIdentity()->getAuthKey());
        return $this->render('_trips', []);
    }


    private function getTrips($filter = [], $limit = 10, $page = 1) {
        $query = null;
        if ($filter) {
            $query = Trip::getTripsFilteredByDate($filter);
        } else {
            $query = Trip::find()
                ->where(["user_id" => \Yii::$app->user->id]);
        }
        $raw_trips = $query
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->orderBy(['start_date_time' => SORT_DESC])
            ->all();
        return Trip::formatRawTrips($raw_trips, $this->_incompletes);
    }


    private function monthToEnglish($string) {
        $string = str_replace('janvier', "january", $string);
        $string = str_replace('février', "february", $string);
        $string = str_replace('mars', "march", $string);
        $string = str_replace('avril', "april", $string);
        $string = str_replace('mai', "may", $string);
        $string = str_replace('juin', "june", $string);
        $string = str_replace('juillet', "july", $string);
        $string = str_replace('août', "august", $string);
        $string = str_replace('septembre', "september", $string);
        $string = str_replace('octobre', "october", $string);
        $string = str_replace('novembre', "november", $string);
        $string = str_replace('décembre', "december", $string);
        return $string;
    }

    private function getTrip() {
        $returnValue = Trip::find()
            ->where(["user_id" => \Yii::$app->user->id])
            ->where(["id" => $this->_singleTripId])
            ->all();
        return $returnValue;
    }
}
