<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;

class ProductsMainGroupsImkuh extends ActiveRecord {

    public static function getDb() {
        return Yii::$app->get('dbImkuh');
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products_main_groups';
    }

}