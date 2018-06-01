<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "manufacturer_has_goods".
 *
 * @property int $manufacturer_id
 * @property int $goods_id
 *
 * @property Goods $goods
 * @property Manufacturer $manufacturer
 */
class ManufacturerHasGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'manufacturer_has_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['manufacturer_id', 'goods_id'], 'required'],
            [['manufacturer_id', 'goods_id'], 'integer'],
            [['manufacturer_id', 'goods_id'], 'unique', 'targetAttribute' => ['manufacturer_id', 'goods_id']],
            [['goods_id'], 'exist', 'skipOnError' => true, 'targetClass' => Goods::class, 'targetAttribute' => ['goods_id' => 'id']],
            [['manufacturer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manufacturer::class, 'targetAttribute' => ['manufacturer_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'manufacturer_id' => 'Manufacturer ID',
            'goods_id' => 'Goods ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::class, ['id' => 'goods_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturer()
    {
        return $this->hasOne(Manufacturer::class, ['id' => 'manufacturer_id']);
    }
}
