<?php
namespace infoweb\taxonomy\assets;

use yii\web\AssetBundle as AssetBundle;

class NestableAsset extends AssetBundle
{
    public $sourcePath = '@infoweb/taxonomy/assets';

    public $js = [
        'js/jquery.nestable.js',
    ];
}
