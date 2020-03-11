<?php

namespace common\widgets\FormDbWidget;

use yii\web\AssetBundle;

class FormDbWidgetAssets extends AssetBundle {
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
        $this->sourcePath = __DIR__ . "/assets";
        parent::init();
    }
}