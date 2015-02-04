<?php
use mihaildev\ckeditor\CKEditor;
?>
<div class="tab-content default-language-tab">
    <?= $form->field($model, "[{$model->language}]name")->textInput([
        'maxlength' => 255,
        'name' => "Lang[{$model->language}][name]"
    ]); ?>

    <?= $form->field($model, "[{$model->language}]content")->widget(CKEditor::className(), [
        'name' => "Lang[{$model->language}][content]",
        'editorOptions' => Yii::$app->getModule('cms')->getCKEditorOptions(),
    ]); ?>
</div>

