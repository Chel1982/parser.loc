<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;

class ProductsImkuh extends ActiveRecord {

    public static function getDb() {
        return Yii::$app->get('dbImkuh');
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products';
    }
}