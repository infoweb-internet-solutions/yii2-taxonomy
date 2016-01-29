<?php
use mihaildev\ckeditor\CKEditor;
use infoweb\cms\helpers\LanguageHelper;
use yii\helpers\ArrayHelper;
?>
<div class="tab-content language-tab">

    <?= $form->field($model, "[{$model->language}]name")->textInput([
        'name' => "Lang[{$model->language}][name]",
        //'data-duplicateable' => Yii::$app->getModule('taxonomy')->allowContentDuplication ? 'true' : 'false'
    ]); ?>

    <?= $form->field($model, "[{$model->language}]content")->widget(CKEditor::className(), [
        'name' => "Lang[{$model->language}][content]",
        'editorOptions' => ArrayHelper::merge(Yii::$app->getModule('cms')->getCKEditorOptions(), (LanguageHelper::isRtl($model->language)) ? ['contentsLangDirection' => 'rtl'] : []),
        //'options' => ['data-duplicateable' => Yii::$app->getModule('taxonomy')->allowContentDuplication ? 'true' : 'false'],
    ]); ?>

</div>