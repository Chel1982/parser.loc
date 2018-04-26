<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;

class ProductsMainGroupsHolodbar extends ActiveRecord {

    public static function getDb() {
        return Yii::$app->get('dbHolodbar');
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products_main_groups';
    }

}