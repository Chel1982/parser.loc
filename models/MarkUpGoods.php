<?php

namespace app\models;

use app\components\validators\GroupsManufValidator;
use app\components\validators\MarkFromValidator;
use app\components\validators\MarkToValidator;
use app\components\validators\CompareValidator;
use Yii;

/**
 * This is the model class for table "mark_up_goods".
 *
 * @property int $id
 * @property int $percent
 * @property int $absolute
 * @property int $price_value
 * @property int $from_value
 * @property int $to_value
 * @property int $categories_imkuh_id
 * @property int $categories_holodbar_id
 * @property int $manufacturer_id_imkuh
 * @property int $manufacturer_id_holodbar
 *
 * @property CategoriesHolodbar $categoriesHolodbar
 * @property CategoriesImkuh $categoriesImkuh
 * @property Manufacturer $manufacturerIdImkuh
 * @property Manufacturer $manufacturerIdHolodbar
 */
class MarkUpGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mark_up_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price_value', 'from_value', 'to_value', 'categories_imkuh_id', 'categories_holodbar_id', 'manufacturer_id_imkuh', 'manufacturer_id_holodbar'], 'integer'],
            [['percent', 'absolute'], 'string', 'max' => 1],
            [['from_value', 'to_value'], CompareValidator::class],
            [['from_value'], MarkFromValidator::class],
            [['to_value'], MarkToValidator::class],
            [['categories_imkuh_id', 'categories_holodbar_id', 'manufacturer_id_imkuh', 'manufacturer_id_holodbar'], GroupsManufValidator::class],
            [['categories_imkuh_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoriesImkuh::class, 'targetAttribute' => ['categories_imkuh_id' => 'pgid']],
            [['categories_holodbar_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoriesHolodbar::class, 'targetAttribute' => ['categories_holodbar_id' => 'pgid']],
            [['manufacturer_id_imkuh'], 'exist', 'skipOnError' => true, 'targetClass' => Manufacturer::class, 'targetAttribute' => ['manufacturer_id_imkuh' => 'id']],
            [['manufacturer_id_holodbar'], 'exist', 'skipOnError' => true, 'targetClass' => Manufacturer::class, 'targetAttribute' => ['manufacturer_id_holodbar' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'percent' => 'В процентах',
            'absolute' => 'Абсолютная величина',
            'price_value' => 'Величина',
            'from_value' => 'Порог(от)',
            'to_value' => 'Порог(до)',
            'categories_imkuh_id' => 'Группы товаров Imkuh',
            'categories_holodbar_id' => 'Группы товаров Holodbar',
            'manufacturer_id_imkuh' => 'Производитель для Imkuh',
            'manufacturer_id_holodbar' => 'Производитель для Holodbar',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesHolodbar()
    {
        return $this->hasOne(CategoriesHolodbar::class, ['pgid' => 'categories_holodbar_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesImkuh()
    {
        return $this->hasOne(CategoriesImkuh::class, ['pgid' => 'categories_imkuh_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturerIdImkuh()
    {
        return $this->hasOne(Manufacturer::class, ['id' => 'manufacturer_id_imkuh']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturerIdHolodbar()
    {
        return $this->hasOne(Manufacturer::class, ['id' => 'manufacturer_id_holodbar']);
    }
}