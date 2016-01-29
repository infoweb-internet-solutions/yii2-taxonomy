<?php

namespace infoweb\taxonomy;

class Module extends \yii\base\Module
{
    /**
     * Allow content duplication with the "duplicateable" plugin
     * @var boolean
     */
    public $allowContentDuplication = true;

    //public $controllerNamespace = 'infoweb\taxonomy\controllers';

    public function init()
    {
        parent::init();
        
        // initialize the module with the configuration loaded from config.php
        \Yii::configure($this, require(__DIR__ . '/config.php'));
    }
}
