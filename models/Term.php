<?php

namespace infoweb\taxonomy\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use dosamigos\translateable\TranslateableBehavior;
use infoweb\taxonomy\behaviors\NestedSetsBehavior;

/**
 * This is the model class for table "taxonomy".
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
     * Get disabled terms for parent dropdown
     *
     * @return mixed
     */
    public function disabledTerms() {

        $results = [];

        if (!$this->getIsNewRecord()) {

            $children = $this->children()->all();

            $results[$this->id] = ['disabled' => true];

            foreach ($children as $n => $child) {
                $results[$child->id] = ['disabled' => true];
            }
        }

        return $results;
    }


}
