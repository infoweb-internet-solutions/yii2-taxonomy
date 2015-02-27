<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model infoweb\taxonomy\models\Term */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' =>  Yii::t('app', 'Taxonomy'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Taxonomy'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="taxonomy-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
