<?php

namespace infoweb\taxonomy\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TermSearch represents the model behind the search form about `infoweb\taxonomy\models\Term`.
 */
class TaxonomySearch extends Term
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'root', 'lft', 'rgt', 'level', 'active', 'created_at', 'updated_at'], 'integer'],
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
        $query = Term::find()->roots();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'active' => $this->active,
        ]);

        return $dataProvider;
    }
}
