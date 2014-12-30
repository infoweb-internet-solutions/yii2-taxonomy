<?php

use yii\bootstrap\Tabs;

// Add the language tabs
foreach (Yii::$app->params['languages'] as $languageId => $languageName) {
    $tabs[] = [
        'label' => $languageName,
        'content' => $this->render('_default_language_tab', ['model' => $model->getTranslation($languageId), 'form' => $form]),
    ];
}

?>
<div class="tab-content default-tab">

    <?= Tabs::widget(['items' => $tabs]); ?>

</div>
