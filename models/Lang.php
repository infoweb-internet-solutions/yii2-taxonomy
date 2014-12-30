<?php

namespace infoweb\catalogue\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ecommerce_categories_lang".
 *
 * @property integer $category_id
 * @property string $language
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Category $category
 */
class Lang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ecommerce_categories_lang';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function() { return time(); },
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // Required
            [['language'], 'required'],
            // Only required for existing records
            [['category_id'], 'required', 'when' => function($model) {
                return !$model->isNewRecord;
            }],
            // Only required for the app language
            [['name'], 'required', 'when' => function($model) {
                return $model->language == Yii::$app->language;
            }],
            // Trim
            [['name'], 'trim'],
            // Types
            [['category_id', 'created_at', 'updated_at'], 'integer'],
            [['language'], 'string', 'max' => 2],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => Yii::t('ecommerce', 'Category ID'),
            'language' => Yii::t('ecommerce', 'Language'),
            'name' => Yii::t('ecommerce', 'Name'),
            'created_at' => Yii::t('ecommerce', 'Created At'),
            'updated_at' => Yii::t('ecommerce', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
}