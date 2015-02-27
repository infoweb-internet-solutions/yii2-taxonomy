<?php

namespace infoweb\taxonomy\models;

use infoweb\taxonomy\behaviors\NestedSetsQueryBehavior;

class TermQuery extends \yii\db\ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}

