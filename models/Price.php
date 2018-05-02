<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "price".
 *
 * @property int $id
 * @property int $price
 * @property int $mark_up_price
 * @property int $goods_id
 *
 * @property Goods $goods
 */
class Price extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price', 'mark_up_price', 'goods_id'], 'integer'],
            [['goods_id'], 'required'],
            [['goods_id'], 'exist', 'skipOnError' => true, 'targetClass' => Goods::className(), 'targetAttribute' => ['goods_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'price' => 'Цена',
            'mark_up_price' => 'Цена с наценкой',
            'goods_id' => 'Goods ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }
}
