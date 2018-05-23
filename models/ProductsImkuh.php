<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
/**
 * This is the model class for table "catalog".
 *
 * @property int $pid
 * @property string $name
 * @property string $text
 * @property string $vendor
 * @property int $on_off
 * @property int $in_case
 * @property int $type
 * @property int $parser_status
 * @property int $price
 * @property int $currency
 *
 */
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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pid' => 'ID',
            'name' => 'Название товара',
            'text' => 'Описание товара',
            'vendor' => 'Производитель товара',
            'on_off' => 'Вкл/Выкл товара',
            'in_case' => 'Наличие товара',
            'type' => 'ID группы',
            'price' => 'Цена товара',
            'currency' => 'Валюта',
            'parser_status' => 'Статус парсера',
        ];
    }
}