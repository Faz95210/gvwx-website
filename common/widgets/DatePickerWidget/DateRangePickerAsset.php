<?php
/**
 * @copyright Copyright (c) 2013-2016 2amigOS! Consulting Group LLC
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace common\widgets\DatePickerWidget;

use yii\web\AssetBundle;

/**
 * DateRangePickerAsset
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package dosamigos\datepicker
 */
class DateRangePickerAsset extends AssetBundle {
    public $sourcePath = 'assets';

    public $css = [
        'css/bootstrap-daterangepicker.css'
    ];

    public $depends = [
        'dosamigos\datepicker\DatePickerAsset'
    ];

}
