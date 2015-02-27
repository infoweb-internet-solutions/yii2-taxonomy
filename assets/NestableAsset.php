<?php
namespace infoweb\taxonomy\assets;

use yii\web\AssetBundle as AssetBundle;

class NestableAsset extends AssetBundle
{
    public $sourcePath = '@bower/nestable2';

    public $js = [
        'js/jquery.nestable.js',
    ];
}
