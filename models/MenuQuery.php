<?php

namespace infoweb\taxonomy\models;

use Yii;
use creocoder\nestedsets\NestedSetsQueryBehavior;
use kartik\icons\Icon;
use yii\helpers\Html;
use yii\helpers\Url;

class MenuQuery extends \yii\db\ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }

    /**
     * Returns a tree of terms
     *
     * @param   int     $root       The id of the root term
     * @return  string              The html output
     */
    public function tree($root = null, $settings = [])
    {
        $model = Term::findOne($root);

        if (!$model)
            return '';

        $terms = $model->leaves()->all();

        // Config options
        $includeReferences = isset($settings['references']) && $settings['references'] == true;
        $level = $model->level;

        $html = Html::beginTag('div', ['class' => 'dd', 'id' => 'sortable']) . PHP_EOL;

        // Add the terms to the html output
        foreach ($terms as $k => $term) {
            // Close the current list-item
            if ($term->level == $level) {
                $html .= Html::endTag('li') . PHP_EOL;
                // Open a new list
            } elseif ($term->level > $level) {
                $html .= "\t" . Html::beginTag('ol', ['class' => 'dd-list', 'data-level' => $term->level - 1]) . PHP_EOL;
                // Close all list-items and lists
            } else {
                $html .= Html::endTag('li') . PHP_EOL;

                for ($i = $level - $term->level; $i; $i--) {
                    $html .= Html::endTag('ol') . PHP_EOL;
                    $html .= Html::endTag('li') . PHP_EOL;
                }
            }

            // Open the list-item
            $html .= "\t\t" . Html::beginTag('li', ['class' => 'dd-item', 'data-term' => $term->id, 'data-url' => Url::toRoute(['term/sort'], true), 'data-type' => 'term']) . PHP_EOL;

            // Add the sortable handle
            $html .= "\t\t\t" . Html::beginTag('div', ['class' => 'dd-handle']);
            $html .= Icon::show('arrows', ['class' => 'fa-fw']);
            $html .= Html::endTag('div') . PHP_EOL;

            // Add the collapse  handles
            $children = $term->leaves()->count();

            if ($children /* || ($includeReferences && count($term->references))*/ ) {
                $html .= "\t\t\t" . Html::beginTag('div', ['class' => 'dd-collapse-handle', 'data-action' => 'collapse']);
                $html .= Icon::show('chevron-down', ['class' => 'fa-fw']);
                $html .= Html::endTag('div') . PHP_EOL;

                $html .= "\t\t\t" . Html::beginTag('div', ['class' => 'dd-collapse-handle', 'data-action' => 'expand']);
                $html .= Icon::show('chevron-right', ['class' => 'fa-fw']);
                $html .= Html::endTag('div') . PHP_EOL;
            } else {
                $html .= "\t\t\t" . Html::beginTag('div', ['class' => 'dd-empty-handle']);
                $html .= '&nbsp;';
                $html .= Html::endTag('div') . PHP_EOL;
            }

            // Add the list-item label
            $html .= "\t\t\t" . Html::beginTag('div', ['class' => 'dd-content' . (($k % 2 == 0) ? ' odd' : ' even')]);
            $html .= Html::encode($term->name);

            $html .= Html::beginTag('span', ['class' => 'action-buttons']);

            // Add the update button
            $html .= Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-pencil']), Url::toRoute(['term/update', 'id' => $term->id]), [
                'data-toggle' => 'tooltip',
                'title' => Yii::t('infoweb/reference', 'Update'),
                'data-pjax' => 0
            ]);

            // Add the delete button
            // Disable delete button when there are items attached to the term

            if (count(/*$term->references*/ 1) > 0) {

                $html .= Html::tag('span', '', [
                    'class' => 'glyphicon glyphicon-trash icon-disabled',
                    'title' => Yii::t('infoweb/reference', "Can't remove term with attached references"),
                    'data-toggle' => 'tooltip',
                ]);

            } else {

                $html .= Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-trash']), Url::toRoute(['term/delete', 'id' => $term->id]), [
                    'data-toggle' => 'tooltip',
                    'title' => Yii::t('infoweb/reference', 'Delete'),
                    'data-id' => "delete-{$term->id}",
                    'data-pjax' => 0,
                    'data-method' => 'post',
                    'data-confirm' => Yii::t('infoweb/reference', 'Are you sure you want to delete this item?'),
                ]);
            }

            // Add the active toggler
            $html .= Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-eye-' . (($term->active == 1) ? 'open' : 'close')]), '#', [
                'data-toggle' => 'tooltip',
                'title' => Yii::t('infoweb/reference', 'Toggle active'),
                'data-pjax' => 0,
                'data-toggler' => 'active',
                'data-id' => $term->id,
                'data-controller' => 'term',
                'data-url' => Url::toRoute(['term/active'], true)
            ]);

            /*
            // Add the images button
            $html .= Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-picture']), ['term-image/index', 'node-id' => $term->id], [
                'data-toggle' => 'tooltip',
                'title' => Yii::t('app', 'Images'),
                'data-pjax' => 0,
            ]);
            */

            $html .= Html::endTag('span') . PHP_EOL;

            // Add the child counter
            if ($children > 0) {
                $html .= Html::tag('span', " ({$children})", ['class' => 'children']);
                // Add the references counter
            } else if ($includeReferences && count($term->references) > 0) {
                $html .= Html::tag('span', " (" . count($term->references) . ")", ['class' => 'children']);
            }

            $html .= Html::endTag('div') . PHP_EOL;

            // Add the references-list
            if ($includeReferences && count($term->references) > 0) {
                $html .= Html::beginTag('ol', ['class' => 'dd-list references', 'data-level' => $term->level - 1]) . PHP_EOL;
                $l = $k + 1;

                foreach ($term->references as $reference) {
                    // Open the list-item
                    $html .= Html::beginTag('li', ['class' => 'dd-item', 'data-term' => $reference->id, 'data-url' => Url::toRoute(['reference/sort'], true), 'data-type' => 'reference', 'data-term' => $term->id]);

                    // Add the list-item label
                    $html .= "\t\t\t" . Html::beginTag('div', ['class' => 'dd-content' . (($l % 2 == 0) ? ' odd' : ' even')]);

                    // Add the sortable handle
                    $html .= "\t\t\t" . Html::beginTag('div', ['class' => 'dd-handle']);
                    $html .= Icon::show('arrows', ['class' => 'fa-fw']);
                    $html .= Html::endTag('div') . PHP_EOL;

                    // Reference main image
                    $html .= Html::img($reference->getImage()->getUrl('30x30'), [
                        'alt'   => '',
                        'style' => 'padding-right: 10px;'
                    ]);
                    $html .= Html::encode($reference->name);

                    $html .= Html::beginTag('span', ['class' => 'action-buttons']);

                    // Add the update button
                    $html .= Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-pencil']), Url::toRoute(['reference/update', 'id' => $reference->id]), [
                        'data-toggle' => 'tooltip',
                        'title' => Yii::t('infoweb/reference', 'Update'),
                        'data-pjax' => 0
                    ]);

                    // Add the delete button
                    $html .= Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-trash']), Url::toRoute(['reference/delete', 'id' => $reference->id]), [
                        'data-toggle' => 'tooltip',
                        'title' => Yii::t('infoweb/reference', 'Delete'),
                        'data-id' => "delete-{$reference->id}",
                        'data-pjax' => 0,
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('infoweb/reference', 'Are you sure you want to delete this item?'),
                    ]);

                    // Add the active toggler
                    $html .= Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-eye-' . (($reference->active == 1) ? 'open' : 'close')]), '#', [
                        'data-toggle' => 'tooltip',
                        'title' => Yii::t('infoweb/reference', 'Toggle active'),
                        'data-pjax' => 0,
                        'data-toggler' => 'active',
                        'data-id' => $reference->id,
                        'data-controller' => 'reference',
                        'data-url' => Url::toRoute(['reference/active'], true)
                    ]);

                    /*
                    // Add the images button
                    $html .= Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-picture']), ['image/index', 'node-id' => $reference->id], [
                        'data-toggle' => 'tooltip',
                        'title' => Yii::t('app', 'Images'),
                        'data-pjax' => 0,
                    ]);
                    */

                    // Close the list-item
                    $html .= Html::endTag('li');

                    $l++;
                }

                $html .= Html::endTag('ol') . PHP_EOL;
            }


            // Update the current level
            $level = $term->level;
        }

        // Close all list-items and lists
        for ($i = $level; $i; $i--) {
            $html .= Html::endTag('li') . PHP_EOL;
            $html .= Html::endTag('ol') . PHP_EOL;
        }

        $html .= Html::endTag('div');

        return $html;
    }

    /**
     * Returns a structured list of terms, formatted for usage in a dropdownlist
     *
     * @param   int     $root       The id of the root category
     * @return  array
     */
    public static function getDropDownListItems($root = null)
    {
        $items = [];

        // Load the provided root category and check if it exists
        $model = Term::findOne($root);

        if (!$model)
            return $items;

        // Load all leaves
        $terms = $model->leaves()->all();

        foreach ($terms as $k => $term) {

            $arrow = '';

            if ($term->level > 0) {
                $arrow = str_repeat("—", ($term->{$model->depthAttribute} * 2));
                $arrow .= "> ";
            }

            $items[$term->id] = $arrow . $term->name;
        }

        return $items;
    }
}