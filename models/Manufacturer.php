<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "manufacturer".
 *
 * @property int $id
 * @property string $name
 * @property string $sites_url
 * @property int $imkuh
 * @property int $holodbar
 *
 * @property ManufacturerHasGoods[] $manufacturerHasGoods
 * @property Goods[] $goods
 * @property MarkUpGoods[] $markUpGoods
 */
class Manufacturer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'manufacturer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['sites_url'], 'string', 'max' => 45],
            [['imkuh', 'holodbar'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Производитель',
            'sites_url' => 'Базовый url',
            'imkuh' => 'Imkuh',
            'holodbar' => 'Holodbar',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturerHasGoods()
    {
        return $this->hasMany(ManufacturerHasGoods::class, ['manufacturer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasMany(Goods::class, ['id' => 'goods_id'])->viaTable('manufacturer_has_goods', ['manufacturer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMarkUpGoods()
    {
        return $this->hasOne(MarkUpGoods::class, ['manufacturer_id' => 'id']);
    }
}
