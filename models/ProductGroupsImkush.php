<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;

class ProductGroupsImkush extends ActiveRecord {

    public static function getDb() {
        return Yii::$app->get('dbImkush');
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products_groups';
    }
}