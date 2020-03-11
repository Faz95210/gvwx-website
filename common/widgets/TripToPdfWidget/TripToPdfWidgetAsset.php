<?php


namespace common\widgets\TripToPdfWidget;


use yii\web\AssetBundle;

class TripToPdfWidgetAsset extends AssetBundle {
    public $js = [
//        'js/DataTables/datatables.js',
        'js/setDataTable.js'
    ];

    public $css = [
        // CDN lib
        'css/style.css'

//        'js/DataTables/datatables.css'
//        'css/MapWidget.css'
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];

    public function init() {
        // Tell AssetBundle where the assets files are
        $this->sourcePath = dirname(__FILE__) . "/assets";
        parent::init();
    }
}