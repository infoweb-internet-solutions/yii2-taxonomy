<?php

use yii\helpers\Html;

use infoweb\taxonomy\models\Term;
use infoweb\taxonomy\TaxonomyAsset;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel infoweb\taxonomy\models\TermSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('ecommerce', 'Terms');
$this->params['breadcrumbs'][] = $this->title;

TaxonomyAsset::register($this);

?>
<div class="term-index">

    <h1>
        <?= Html::encode($this->title) ?>
        <?php // Buttons ?>
        <div class="pull-right">
            <?= Html::a(Yii::t('app', 'Create {modelClass}', [
                'modelClass' => Yii::t('ecommerce', 'Term'),
            ]), ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    </h1>

    <?php // Flash messages ?>
    <?php echo $this->render('_flash_messages'); ?>

    <?php Pjax::begin(['id'=>'pjax-container']); ?>
    <table class="table table-bordered" style="margin: 20px 0 0 0;">
        <thead>
        <tr>
            <th>Naam</th>
            <th class="actions">Acties</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="2">
                <?= $tree; ?>
            </td>
        </tr>
        </tbody>
    </table>
    <?php Pjax::end(); ?>

</div>
