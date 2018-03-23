<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "goods_has_manufacturer".
 *
 * @property int $goods_id
 * @property int $manufacturer_id
 *
 * @property Goods $goods
 * @property Manufacturer $manufacturer
 */
class GoodsHasManufacturer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_has_manufacturer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'manufacturer_id'], 'required'],
            [['goods_id', 'manufacturer_id'], 'integer'],
            [['goods_id', 'manufacturer_id'], 'unique', 'targetAttribute' => ['goods_id', 'manufacturer_id']],
            [['goods_id'], 'exist', 'skipOnError' => true, 'targetClass' => Goods::className(), 'targetAttribute' => ['goods_id' => 'id']],
            [['manufacturer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manufacturer::className(), 'targetAttribute' => ['manufacturer_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'goods_id' => 'Goods ID',
            'manufacturer_id' => 'Manufacturer ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturer()
    {
        return $this->hasOne(Manufacturer::className(), ['id' => 'manufacturer_id']);
    }
}
