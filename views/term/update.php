<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model infoweb\taxonomy\models\Term */

$this->title = Yii::t('infoweb/cms', 'Update {modelClass}', [
    'modelClass' => Yii::t('infoweb/taxonomy', 'Term'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('infoweb/taxonomy', 'Terms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('infoweb/cms', 'Update');
?>
<div class="term-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'terms' => $terms,
        'parent_id' => $parent_id,
    ]) ?>

</div>
