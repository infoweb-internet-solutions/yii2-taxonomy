<?php

namespace infoweb\taxonomy\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use dosamigos\translateable\TranslateableBehavior;
use wbraganca\behaviors\NestedSetBehavior;
use wbraganca\behaviors\NestedSetQuery;

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
            [
                'class' => NestedSetBehavior::className(),
            ],
        ];
    }

    public static function find()
    {
        return new NestedSetQuery(get_called_class());
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
            'id' => Yii::t('ecommerce', 'ID'),
            'root' => Yii::t('ecommerce', 'Root'),
            'lft' => Yii::t('ecommerce', 'Lft'),
            'rgt' => Yii::t('ecommerce', 'Rgt'),
            'level' => Yii::t('ecommerce', 'Level'),
            'active' => Yii::t('ecommerce', 'Active'),
            'created_at' => Yii::t('ecommerce', 'Created At'),
            'updated_at' => Yii::t('ecommerce', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(Lang::className(), ['term_id' => 'id']);
    }
}
