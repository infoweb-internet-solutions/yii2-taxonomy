<?php
use yii\helpers\Html;
use kartik\switchinput\SwitchInput;
?>
<div class="tab-content data-tab">

    <div class="form-group field-term-parent_id">
        <label for="term-parent_id" class="control-label"><?= Yii::t('app', 'Parent'); ?></label>
        <?= Html::dropDownList('Term[parent_id]', $parent_id, $terms, [
            'class' => 'form-control',
            'id' => 'term-parent_id',
            'options' => $model->disabledTerms(),
        ]) ?>
        <div class="help-block"></div>
    </div>

    <?php echo $form->field($model, 'active')->widget(SwitchInput::classname(), [
        'inlineLabel' => false,
        'pluginOptions' => [
            'onColor' => 'success',
            'offColor' => 'danger',
            'onText' => Yii::t('app', 'Yes'),
            'offText' => Yii::t('app', 'No'),
        ]
    ]); ?>

</div>