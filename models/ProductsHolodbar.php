<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;

class ProductsHolodbar extends ActiveRecord {

    public static function getDb() {
        return Yii::$app->get('dbHolodbar');
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductsMainGroups()
    {
        return $this->hasMany(ProductsMainGroupsHolodbar::className(), ['pmgid' => 'pmgid']);
    }
}