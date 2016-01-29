<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\bootstrap\Tabs;
?>
<div class="taxonomy-form">
    
    <?php // Flash messages ?>
    <?php echo $this->render('_flash_messages'); ?>

    <?php
    // Init the form
    $form = ActiveForm::begin([
        'id'                        => 'taxonomy-form',
        'options'                   => ['class' => 'tabbed-form', 'enctype' => 'multipart/form-data'],
        'enableAjaxValidation'      => false,
        'enableClientValidation'    => false,
    ]);

    // Initialize the tabs
    $tabs = [];

    // Add the main tabs
    $tabs = [
        [
            'label' => Yii::t('app', 'General'),
            'content' => $this->render('_default_tab', ['model' => $model, 'form' => $form]),
            'active' => true,
        ],
        [
            'label' => Yii::t('app', 'Data'),
            'content' => $this->render('_data_tab', ['model' => $model, 'form' => $form]),
            //'visible' => false,
        ],
    ];

    // Display the tabs
    echo Tabs::widget(['items' => $tabs]);
    ?>
    
    <div class="form-group buttons">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create & close') : Yii::t('app', 'Update & close'), ['class' => 'btn btn-default', 'name' => 'close']) ?>
        <?= Html::submitButton(Yii::t('app', $model->isNewRecord ? 'Create & new' : 'Update & new'), ['class' => 'btn btn-default', 'name' => 'new']) ?>
        <?= Html::a(Yii::t('app', 'Close'), ['index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>