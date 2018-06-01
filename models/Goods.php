<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property int $id
 * @property string $name_goods
 * @property string $uri_goods
 * @property double $price
 * @property double $mark_up_price
 * @property string $currency
 * @property int $availability
 * @property int $groups_id
 * @property int $sites_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Description[] $descriptions
 * @property Groups $groups
 * @property Sites $sites
 * @property Images[] $images
 * @property Logs[] $logs
 * @property ManufacturerHasGoods[] $manufacturerHasGoods
 * @property Manufacturer[] $manufacturers
 * @property ProductAttributes[] $productAttributes
 */
class Goods extends \yii\db\ActiveRecord
{
    public $from_date;
    public $to_date;
    public $groups_name;
    public $manufacturers_name;
    public $price_from;
    public $price_to;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price', 'mark_up_price'], 'number'],
            [['groups_id', 'sites_id'], 'required'],
            [['groups_id', 'sites_id', 'availability'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name_goods', 'uri_goods'], 'string', 'max' => 255],
            [['currency'], 'string', 'max' => 45],
            //[['availability'], 'string', 'max' => 1],
            [['uri_goods'], 'unique'],
            [['groups_id'], 'exist', 'skipOnError' => true, 'targetClass' => Groups::class, 'targetAttribute' => ['groups_id' => 'id']],
            [['sites_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sites::class, 'targetAttribute' => ['sites_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID товара',
            'name_goods' => 'Название товара',
            'uri_goods' => 'url товара',
            'created_at' => 'Дата скачивания',
            'updated_at' => 'Дата обновления',
            'sites_id' => 'Название сайта товара',
            'groups_id' => 'Название группы',
            'groups_name' => 'Название группы товара',
            'manufacturers_name' => 'Название производителя товара',
            'price_from' => 'Цена товара (от)',
            'price_to' => 'Цена товара (до)',
            'price' => 'Цена',
            'mark_up_price' => 'Наценка',
            'currency' => 'Валюта',
            'availability' => 'Наличие',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDescriptions()
    {
        return $this->hasOne(Description::class, ['goods_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasOne(Groups::class, ['id' => 'groups_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSites()
    {
        return $this->hasOne(Sites::class, ['id' => 'sites_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Images::class, ['goods_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogs()
    {
        return $this->hasOne(Logs::class, ['goods_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturerHasGoods()
    {
        return $this->hasMany(ManufacturerHasGoods::class, ['goods_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturers()
    {
        return $this->hasOne(Manufacturer::class, ['id' => 'manufacturer_id'])->viaTable('manufacturer_has_goods', ['goods_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttributes()
    {
        return $this->hasOne(ProductAttributes::class, ['goods_id' => 'id']);
    }
}