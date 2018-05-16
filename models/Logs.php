<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "logs".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $desc_add
 * @property string $image
 * @property string $price
 * @property string $manufactured
 * @property string $prod_attr
 * @property string $availability
 * @property int $goods_id
 *
 * @property Goods $goods
 */
class Logs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'logs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'desc_add', 'image', 'price', 'manufactured', 'prod_attr', 'availability'], 'string'],
            [['goods_id'], 'required'],
            [['goods_id'], 'integer'],
            [['goods_id'], 'exist', 'skipOnError' => true, 'targetClass' => Goods::class, 'targetAttribute' => ['goods_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'description' => 'Описание',
            'desc_add' => 'Дополнительное описание',
            'image' => 'Изображение',
            'price' => 'Цена',
            'manufactured' => 'Производитель',
            'prod_attr' => 'Аттрибуты продукции',
            'availability' => 'Наличие товара',
            'goods_id' => 'ID товара',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::class, ['id' => 'goods_id']);
    }
}
