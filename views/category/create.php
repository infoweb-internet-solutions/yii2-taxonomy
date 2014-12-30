<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model infoweb\catalogue\models\Category */

$this->title = Yii::t('ecommerce', 'Create {modelClass}', [
    'modelClass' => 'Category',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('ecommerce', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'terms' => $terms,
        'parent_id' => $parent_id,
    ]) ?>

</div>
