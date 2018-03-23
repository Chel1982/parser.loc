<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "xpath".
 *
 * @property int $id
 * @property string $regular
 * @property int $sites_id
 * @property int $name_regular_id
 *
 * @property NameRegular $nameRegular
 * @property Sites $sites
 */
class Xpath extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'xpath';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sites_id', 'name_regular_id'], 'required'],
            [['sites_id', 'name_regular_id'], 'integer'],
            [['regular'], 'string', 'max' => 255],
            [['name_regular_id'], 'exist', 'skipOnError' => true, 'targetClass' => NameRegular::className(), 'targetAttribute' => ['name_regular_id' => 'id']],
            [['sites_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sites::className(), 'targetAttribute' => ['sites_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'regular' => 'XPath выражение',
            'sites_id' => 'Название сайта',
            'name_regular_id' => 'Название регулярного выражения',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNameRegular()
    {
        return $this->hasOne(NameRegular::className(), ['id' => 'name_regular_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSites()
    {
        return $this->hasOne(Sites::className(), ['id' => 'sites_id']);
    }
}
