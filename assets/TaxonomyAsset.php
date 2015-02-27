<?php
namespace infoweb\taxonomy\assets;

use yii\web\AssetBundle as AssetBundle;

class TaxonomyAsset extends AssetBundle
{
    public $sourcePath = '@infoweb/taxonomy/assets/';
    
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
        'infoweb\taxonomy\assets\NestableAsset',
        'infoweb\taxonomy\assets\CookieAsset',
    ];
}