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
        $this->sourcePath = __DIR__ . "/assets";
        $this->basePath = __DIR__ . "/assets";
        parent::init();
    }
}