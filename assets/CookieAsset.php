<?php
namespace infoweb\taxonomy\assets;

use yii\web\AssetBundle as AssetBundle;

class CookieAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-cookie/src';

    public $js = [
        'jquery.cookie.js'
    ];
}
