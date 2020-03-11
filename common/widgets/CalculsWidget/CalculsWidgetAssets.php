<?php


namespace common\widgets\CalculsWidget;


use yii\web\AssetBundle;

class CalculsWidgetAssets extends AssetBundle {
    public $images = [
    ];

    public $js = [
    ];

    public $css = [
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];

    public function init() {
        $this->sourcePath = dirname(__FILE__) . "/assets";
        $this->basePath = dirname(__FILE__) . "/assets";
        parent::init();
    }


}