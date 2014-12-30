<?php
namespace infoweb\catalogue;

use yii\web\AssetBundle as AssetBundle;

class TaxonomyAsset extends AssetBundle
{
    public $sourcePath = '@infoweb/catalogue/assets/';
    
    public $css = [
        'css/nestedSortable.css',
        'css/main.css',
    ];
    
    public $js = [
        'js/main.js',
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'infoweb\cms\CMSAsset',
        'infoweb\catalogue\assets\NestableAsset',
        'infoweb\catalogue\assets\CookieAsset',
    ];
}