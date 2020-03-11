<?php

namespace common\widgets\NotificationWidget;

use yii\base\Widget;
use yii\web\AssetBundle;

class NotificationWidgetAssets extends AssetBundle {

    public $images = [
    ];

    public $js = [
    ];

    public $css = [
        '//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css',
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