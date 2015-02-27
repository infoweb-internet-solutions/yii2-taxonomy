<?php

namespace infoweb\taxonomy\models;

use creocoder\nestedsets\NestedSetsQueryBehavior;

class TermQuery extends \yii\db\ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}

