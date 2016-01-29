<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => Yii::t('infoweb/taxonomy', 'Taxonomy'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('infoweb/taxonomy', 'Taxonomy'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="taxonomy-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>