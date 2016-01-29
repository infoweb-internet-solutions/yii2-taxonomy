<?php

namespace infoweb\taxonomy\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use infoweb\taxonomy\models\Term;

/**
 * TermSearch represents the model behind the search form about `infoweb\taxonomy\models\Term`.
 */
class Search extends Term
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['root'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {

        $query = Term::find()->joinWith('translations')->roots();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['name' => $this->name]);

        return $dataProvider;
    }
}
