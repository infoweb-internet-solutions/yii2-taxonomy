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
    // @todo Update code
    public function sortableTree($root = 1, $depth = 0)
    {
        $model = Term::findOne($root);

        $terms = $model->find()->addOrderBy('lft')->where("{$model->depthAttribute} > {$depth}")->all();

        $res = Html::beginTag('div', ['class' => 'dd', 'id' => 'sortable']) . PHP_EOL;

        foreach ($terms as $n => $term)
        {
            if ($term->{$model->depthAttribute} == $depth) {
                $res .= Html::endTag('li') . PHP_EOL;
            } elseif ($term->{$model->depthAttribute} > $depth) {
                $res .= Html::beginTag('ol', ['class' => 'dd-list', 'data-level' => $term->{$model->depthAttribute} - 1]) . PHP_EOL;
            } else {
                $res .= Html::endTag('li') . PHP_EOL;

                for ($i = $depth - $term->{$model->depthAttribute}; $i; $i--) {
                    $res .= Html::endTag('ol') . PHP_EOL;
                    $res .= Html::endTag('li') . PHP_EOL;
                }
            }

            $res .= Html::beginTag('li', ['class' => 'dd-item', 'data-term' => $term->id]) . PHP_EOL;

            //$res .= Html::beginTag('div', ['class' => (($n%2==0) ? 'odd' : 'even') /*, 'style' => 'padding-left: ' . (30 * ($model->level - 1)) . 'px'*/]);

            $res .= Html::beginTag('div', ['class' => 'dd-handle']);
            $res .= Icon::show('arrows', ['class' => 'fa-fw']);
            $res .= Html::endTag('div') . PHP_EOL;

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

            $res .= Html::endTag('div') . PHP_EOL;

            //$res .= Html::endTag('div') . PHP_EOL;

            $depth = $term->{$model->depthAttribute};
        }

        for ($i = $depth; $i; $i--) {
            $res .= Html::endTag('li') . PHP_EOL;
            $res .= Html::endTag('ol') . PHP_EOL;
        }

        $res .= Html::endTag('div');

        return $res;

    }


    /**
     * Returns a structured list of terms, formatted for usage in a dropdownlist
     *
     * @param   int     $root       The id of the root category
     * @return  array
     */
    public static function dropDownListItems($root = null)
    {
        $items = [];

        // Load the provided root category and check if it exists
        $rootTerm = Term::findOne($root);

        if (!$rootTerm)
            return $items;

        // Load all categories except the root category
        $terms = Term::find()->where(['tree' => $rootTerm->id])->addOrderBy($rootTerm->leftAttribute)->all();

        foreach ($terms as $k => $term) {

            $arrow = '';

            if ($term->{$term->depthAttribute} > 0) {
                $arrow = str_repeat("—", ($term->{$rootTerm->depthAttribute} * 2));
                $arrow .= "> ";
            }

            $items[$term->id] = $arrow . $term->name;
        }

        return $items;
    }
}