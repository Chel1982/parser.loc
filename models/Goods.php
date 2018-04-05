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
 * @property string $from_date
 * @property string $to_date
 * @property string $groups_name
 * @property int $sites_id
 * @property int $groups_id
 *
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
            [['created_at'], 'safe'],
            [['sites_id', 'groups_id'], 'required'],
            [['sites_id', 'groups_id'], 'integer'],
            [['name_goods', 'uri_goods'], 'string', 'max' => 255],
            [['uri_goods'], 'unique'],
            [['groups_id'], 'exist', 'skipOnError' => true, 'targetClass' => Groups::className(), 'targetAttribute' => ['groups_id' => 'id']],
            [['sites_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sites::className(), 'targetAttribute' => ['sites_id' => 'id']],
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
            'uri_goods' => 'uri товара',
            'created_at' => 'Дата скачивания',
            'sites_id' => 'Название сайта товара',
            'groups_id' => 'Groups ID',
            'groups_name' => 'Название группы товара',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDescriptions()
    {
        return $this->hasOne(Description::className(), ['goods_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasOne(Groups::className(), ['id' => 'groups_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSites()
    {
        return $this->hasOne(Sites::className(), ['id' => 'sites_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Images::className(), ['goods_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogs()
    {
        return $this->hasOne(Logs::className(), ['goods_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturers()
    {
        return $this->hasOne(Manufacturer::className(), ['goods_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrices()
    {
        return $this->hasOne(Price::className(), ['goods_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttributes()
    {
        return $this->hasOne(ProductAttributes::className(), ['goods_id' => 'id']);
    }
}
