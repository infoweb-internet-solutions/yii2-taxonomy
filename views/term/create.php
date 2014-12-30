<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model infoweb\taxonomy\models\Term */

$this->title = Yii::t('ecommerce', 'Create {modelClass}', [
    'modelClass' => 'Term',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('ecommerce', 'Terms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="term-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'terms' => $terms,
        'parent_id' => $parent_id,
    ]) ?>

</div>
