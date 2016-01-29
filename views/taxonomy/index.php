<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('infoweb/taxonomy', 'Taxonomy');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="taxonomy-index">

    <?php // Title ?>
    <h1>
        <?= Html::encode($this->title) ?>
        <?php // Buttons ?>
        <div class="pull-right">
            <?= Html::a(Yii::t('app', 'Create {modelClass}', [
                'modelClass' => Yii::t('infoweb/taxonomy', 'Taxonomy'),
            ]), ['create'], ['class' => 'btn btn-success']) ?>    
        </div>
    </h1>
    
    <?php // Flash messages ?>
    <?php echo $this->render('_flash_messages'); ?>

    <?php // Gridview ?>
    <?php echo GridView::widget([
        'dataProvider'=> $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'name',
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => (Yii::$app->user->can('Superadmin')) ? '{update} {delete} {term}' : '{update} {term}',
                'buttons' => [
                    'term' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', $url, [
                            'title' => Yii::t('infoweb/taxonomy', 'Terms'),
                            'data-pjax' => '0',
                            'data-toggle' => 'tooltip',
                        ]);
                    },
                ],
                'urlCreator' => function($action, $model, $key, $index) {
                    if ($action === 'term') {
                        return Url::toRoute(['/taxonomy/term/index', 'taxonomy-id' => $model->id]);
                    } else {
                        return Url::toRoute([$action, 'id' => $key]);
                    }
                },
                'updateOptions' => ['title' => Yii::t('app', 'Update'), 'data-toggle' => 'tooltip'],
                'deleteOptions' => ['title' => Yii::t('app', 'Delete'), 'data-toggle' => 'tooltip'],
                'width' => '120px',
            ],
        ],
    ]);
    ?>

</div>