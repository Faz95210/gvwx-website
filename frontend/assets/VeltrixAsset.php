<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Yannick SILVA
 * @since 1.0
 */
class VeltrixAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/veltrix/bootstrap.css',
        'css/veltrix/metismenu.min.css',
        'css/veltrix/icons.css',
        'css/veltrix/style.css',
    ];
    public $js = [
        //'js/veltrix/bootstrap.bundle.min.js',
        'js/veltrix/metisMenu.min.js',
        'js/veltrix/jquery.slimscroll.js',
        'js/veltrix/waves.min.js',
        'js/veltrix/peity-chart/jquery.peity.min.js',
        'js/loadModal.js',
        'js/sweetalertDeleteItem.js',
        'js/veltrix/app.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
