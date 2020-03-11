<?php


namespace common\widgets\MailWidget;


use yii\web\AssetBundle;

class MailWidgetAsset extends AssetBundle {

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
        parent::init();
    }
}