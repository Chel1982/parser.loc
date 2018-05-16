<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property int $id
 * @property string $name_goods
 * @property string $uri_goods
 * @property string $created_at
 * @property string $updated_at
 * @property string $from_date
 * @property string $to_date
 * @property string $groups_name
 * @property int $sites_id
 * @property int $groups_id
 * @property int $duplicate_imkuh
 * @property int $duplicate_holodbar
 *
 * @property Availability[] $availabilities
 * @property Description[] $descriptions
 * @property Groups $groups
 * @property Sites $sites
 * @property Images[] $images
 * @property Logs[] $logs
 * @property Manufacturer[] $manufacturers
 * @property Price[] $prices
 * @property ProductAttributes[] $productAttributes
 */
class Goods extends \yii\db\ActiveRecord
{
    public $from_date;
    public $to_date;
    public $groups_name;

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
            [['created_at', 'updated_at'], 'safe'],
            [['sites_id', 'groups_id'], 'required'],
            [['sites_id', 'groups_id', 'duplicate_imkuh', 'duplicate_holodbar'], 'integer'],
            [['duplicate_imkuh', 'duplicate_holodbar'], 'integer', 'max' => 1],
            [['name_goods', 'uri_goods'], 'string', 'max' => 255],
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
            'groups_id' => 'ID группы',
            'groups_name' => 'Название группы товара',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAvailabilities()
    {
        return $this->hasOne(Availability::class, ['goods_id' => 'id']);
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
    public function getManufacturers()
    {
        return $this->hasOne(Manufacturer::class, ['goods_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrices()
    {
        return $this->hasOne(Price::class, ['goods_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttributes()
    {
        return $this->hasOne(ProductAttributes::class, ['goods_id' => 'id']);
    }
}
