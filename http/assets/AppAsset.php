<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/bootstrap-material-design.min.css',
        'css/ripples.min.css',
        'css/jquery-ui.css'
    ];
    public $js = [
        'js/material.min.js',
        'js/ripples.min.js',
        'js/material.min.js',
        'js/jquery-ui.js',
        'js/jquery.number.min.js',
        'js/moment-with-locales.min.js',
        'js/highcharts.js',
        'js/main.js',
        'js/autocompleter.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset'
    ];
}
