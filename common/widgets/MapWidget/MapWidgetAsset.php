<?php
// your_app/votewidget/VoteWidgetAsset.php

namespace common\widgets\MapWidget;

use yii\web\AssetBundle;

class MapWidgetAsset extends AssetBundle {

    public $images = [
        'images/marker.png'
    ];

    public $js = [
        'js/leaflet/leaflet.js',
        'js/leaflet/leaflet.routing.js',
        'js/leaflet/L.KML.js',
        'js/nc/MapBasics.js',
        'js/nc/NCEditable.js',
        'js/nc/NCEditableRoute.js',
        'js/nc/NCMarker.js',
        'js/nc/KMLHandler.js',
        'js/nc/TrackingHandler.js',
        'js/geojsonToKml.js'
    ];

    public $css = [
        '//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css',
        'css/MapWidget.css',
        'css/leaflet/leaflet.css',
        'css/leaflet/leaflet.routing.css',
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
