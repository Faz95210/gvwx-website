<?php


namespace common\widgets\IncompleteAddressWidget;


use yii\web\AssetBundle;

class IncompleteAddressWidgetAssets extends AssetBundle {
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
        $this->sourcePath = __DIR__ . "/assets";
        $this->basePath = __DIR__ . "/assets";
        parent::init();
    }


}