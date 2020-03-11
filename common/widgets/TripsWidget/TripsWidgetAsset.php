<?php

namespace common\widgets\TripsWidget;

use yii\web\AssetBundle;

class TripsWidgetAsset extends AssetBundle {
    public $js = [
//        'js/DataTables/datatables.js',
//        'js/setDataTable.js'
    ];

    public $css = [
        // CDN lib
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
