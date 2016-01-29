<?php
use yii\helpers\Html;

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => Yii::t('infoweb/taxonomy', 'Taxonomy'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('infoweb/taxonomy', 'Taxonomy'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="taxonomy-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
