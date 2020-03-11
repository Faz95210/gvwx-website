<?php


namespace common\widgets\PaymentInformationsWidget;


use yii\web\AssetBundle;

class PaymentInformationsWidgetAssets extends AssetBundle {

    public $js = [
//        'js/stripe.js',
        'js/script.js',
//        'js/imask.js',
//        'js/md5.js',
    ];

    public $css = [
        // CDN lib
        'css/style.css'
//        'css/MapWidget.css'
    ];

    public function init() {
        // Tell AssetBundle where the assets files are
        $this->sourcePath = dirname(__FILE__) . "/assets";
        parent::init();
    }
}