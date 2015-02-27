<?php

namespace infoweb\taxonomy\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use dosamigos\translateable\TranslateableBehavior;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\helpers\Html;
use kartik\icons\Icon;

/**
 * This is the model class for table "taxonomy".
 *
 * @property string $id
 * @property string $root
 * @property string $lft
 * @property string $rgt
 * @property integer $level
 * @property integer $active
 * @property string $created_at
 * @property string $updated_at
 */
class Term extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'terms';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'trans' => [
                'class' => TranslateableBehavior::className(),
                'translationAttributes' => [
                    'name',
                    'content',
                ],
            ],
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function() { return time(); },
            ],
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new TermQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['root', 'lft', 'rgt', 'level', 'active', 'created_at', 'updated_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'root' => Yii::t('app', 'Root'),
            'parent' => Yii::t('app', 'Parent'),
            'level' => Yii::t('app', 'Level'),
            'active' => Yii::t('app', 'Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(Lang::className(), ['term_id' => 'id']);
    }

    /**
     * Hack for category routes
     *
     * @return array
     */
    public function getUrl() {

        if ($this->id == 4) {
            $url = ['site/gallery', 'term-id' => $this->id];
        } elseif ($this->id == 9) {
            $url = ['site/team', 'team-id' => $this->id];
        } else {
            $url = ['site/news', 'term-id' => $this->id];
        }

        return $url;
    }

    public static function sortableTree($root = 1, $level = null)
    {
        $terms = Term::find()->leaves()->all();

        $newLine = "\n";

        $res = Html::beginTag('div', ['class' => 'dd', 'id' => 'sortable']) . $newLine;

        foreach ($terms as $n => $term)
        {
            if ($term->level == $level) {
                $res .= Html::endTag('li') . $newLine;
            } elseif ($term->level > $level) {
                $res .= Html::beginTag('ol', ['class' => 'dd-list', 'data-level' => $term->level - 1]) . $newLine;
            } else {
                $res .= Html::endTag('li') . $newLine;

                for ($i = $level - $term->level; $i; $i--) {
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
                'title' => Yii::t('ecommerce', 'Update'),
                'data-pjax' => 0
            ]);
            $res .= Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-trash']), Url::toRoute(['delete', 'id' => $term->id]), [
                'data-toggle' => 'tooltip',
                'title' => Yii::t('ecommerce', 'Delete'),
                'data-id' => "delete-{$term->id}",
                'data-pjax' => 0,
                'data-method' => 'post',
                'data-confirm' => Yii::t('ecommerce', 'Are you sure you want to delete this item?'),
            ]);
            $res .= Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-eye-' . (($term->active == 1) ? 'open' : 'close')]), '#', [
                'data-toggle' => 'tooltip',
                'title' => Yii::t('ecommerce', 'Toggle active'),
                'data-pjax' => 0,
                'data-toggle-active-term' => $term->id,
            ]);

            $res .= Html::endTag('span');

            $children = $term->descendants()->count();
            if ($children > 0) {
                $res .= Html::tag('span', " ({$children})", ['class' => 'children']) ;
            }

            $res .= Html::endTag('div') . $newLine;

            //$res .= Html::endTag('div') . $newLine;

            $level = $term->level;
        }

        for ($i = $level; $i; $i--) {
            $res .= Html::endTag('li') . $newLine;
            $res .= Html::endTag('ol') . $newLine;
        }

        $res .= Html::endTag('div');

        return $res;

    }
}
