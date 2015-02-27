<?php
/**
 * @link https://github.com/creocoder/yii2-nested-sets
 * @copyright Copyright (c) 2015 Alexander Kochetov
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace infoweb\taxonomy\behaviors;

use creocoder\nestedsets\NestedSetsQueryBehavior as BaseNestedSetsQueryBehavior;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\icons\Icon;

use infoweb\taxonomy\models\Term;

/**
 * NestedSetsQueryBehavior
 *
 * @property \yii\db\ActiveQuery $owner
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 */
class NestedSetsQueryBehavior extends BaseNestedSetsQueryBehavior
{
    public function sortableTree($root = 1, $level = null)
    {
        $model = new $this->owner->modelClass();

        $terms = Term::find()->leaves()->all();

        //echo '<pre>'; print_r($terms); echo '</pre>'; exit();

        $newLine = "\n";

        $res = Html::beginTag('div', ['class' => 'dd', 'id' => 'sortable']) . $newLine;

        foreach ($terms as $n => $term)
        {
            if ($term->{$model->depthAttribute} == $level) {
                $res .= Html::endTag('li') . $newLine;
            } elseif ($term->{$model->depthAttribute} > $level) {
                $res .= Html::beginTag('ol', ['class' => 'dd-list', 'data-level' => $term->{$model->depthAttribute} - 1]) . $newLine;
            } else {
                $res .= Html::endTag('li') . $newLine;

                for ($i = $level - $term->{$model->depthAttribute}; $i; $i--) {
                    $res .= Html::endTag('ol') . $newLine;
                    $res .= Html::endTag('li') . $newLine;
                }
            }

            $res .= Html::beginTag('li', ['class' => 'dd-item', 'data-term' => $term->id]) . $newLine;

            //$res .= Html::beginTag('div', ['class' => (($n%2==0) ? 'odd' : 'even') /*, 'style' => 'padding-left: ' . (30 * ($model->level - 1)) . 'px'*/]);

            $res .= Html::beginTag('div', ['class' => 'dd-handle']);
            $res .= Icon::show('arrows', ['class' => 'fa-fw']);
            $res .= Html::endTag('div') . $newLine;

            $res .= Html::beginTag('div', ['class' => 'dd-content' . (($n%2==0) ? ' odd' : ' even')]);
            $res .= Html::encode($term->name);

            $res .= Html::beginTag('span', ['class' => 'action-buttons']);
            $res .= Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-pencil']), Url::toRoute(['update', 'id' => $term->id]), [
                'data-toggle' => 'tooltip',
                'title' => Yii::t('app', 'Update'),
                'data-pjax' => 0
            ]);
            $res .= Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-trash']), Url::toRoute(['delete', 'id' => $term->id]), [
                'data-toggle' => 'tooltip',
                'title' => Yii::t('app', 'Delete'),
                'data-id' => "delete-{$term->id}",
                'data-pjax' => 0,
                'data-method' => 'post',
                'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
            ]);
            $res .= Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-eye-' . (($term->active == 1) ? 'open' : 'close')]), '#', [
                'data-toggle' => 'tooltip',
                'title' => Yii::t('app', 'Toggle active'),
                'data-pjax' => 0,
                'data-toggle-active-term' => $term->id,
            ]);

            $res .= Html::endTag('span');

            $children = $term->leaves()->count();
            if ($children > 0) {
                $res .= Html::tag('span', " ({$children})", ['class' => 'children']) ;
            }

            $res .= Html::endTag('div') . $newLine;

            //$res .= Html::endTag('div') . $newLine;

            $level = $term->{$model->depthAttribute};
        }

        for ($i = $level; $i; $i--) {
            $res .= Html::endTag('li') . $newLine;
            $res .= Html::endTag('ol') . $newLine;
        }

        $res .= Html::endTag('div');

        return $res;

    }
}
