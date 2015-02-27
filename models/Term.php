<?php

namespace infoweb\taxonomy\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use dosamigos\translateable\TranslateableBehavior;
use creocoder\behaviors\NestedSetBehavior;
use creocoder\behaviors\NestedSetQuery;

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
            'id' => Yii::t('infoweb/taxonomy', 'ID'),
            'root' => Yii::t('infoweb/taxonomy', 'Root'),
            'parent' => Yii::t('infoweb/taxonomy', 'Parent'),
            'level' => Yii::t('infoweb/taxonomy', 'Level'),
            'active' => Yii::t('infoweb/cms', 'Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(Lang::className(), ['term_id' => 'id']);
    }

    public function getNews() {
        return $this->hasMany(News::className(), ['term_id' => 'id'])->orderBy(['date' => SORT_DESC]);
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
}
