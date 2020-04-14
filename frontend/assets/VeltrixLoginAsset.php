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
class VeltrixLoginAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/veltrix/chartist/css/chartist.min.css',
        'css/veltrix/bootstrap.min.css',
        'css/veltrix/metismenu.min.css',
        'css/veltrix/icons.css',
        'css/veltrix/style.css',
    ];
    public $js = [
        //'js/veltrix/jquery.min.js',
        'js/veltrix/bootstrap.bundle.min.js',
        'js/veltrix/metisMenu.min.js',
        'js/veltrix/jquery.slimscroll.js',
        'js/veltrix/waves.min.js',
        'js/veltrix/plugins/countdown/jquery.countdown.min.js',
        'js/veltrix/pages/countdown.int.js',
        'js/veltrix/app.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
