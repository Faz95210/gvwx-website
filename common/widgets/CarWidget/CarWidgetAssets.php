<?php

namespace common\widgets\CarWidget;

use yii\web\AssetBundle;

class CarWidgetAssets extends AssetBundle {

    public $js = [

    ];

    public $css = [
        // CDN lib
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