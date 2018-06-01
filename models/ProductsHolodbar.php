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
 * @property int $on_off
 * @property int $in_case
 * @property int $type
 * @property int $parser_status
 * @property int $price
 * @property int $valuta
 *
 */

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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pid' => 'ID',
            'name' => 'Название товара',
            'text' => 'Описание товара',
            'on_off' => 'Вкл/Выкл товара',
            'in_case' => 'Наличие товара',
            'type' => 'ID группы',
            'price' => 'Цена товара',
            'valuta' => 'Валюта',
            'parser_status' => 'Статус парсера',
        ];
    }
}