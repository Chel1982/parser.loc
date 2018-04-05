<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "logs_curl".
 *
 * @property int $id
 * @property string $name
 * @property string $name_daemons
 * @property int $goods_id
 * @property int $sites_id
 *
 * @property Sites $sites
 */
class LogsCurl extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'logs_curl';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
            [['goods_id', 'sites_id'], 'integer'],
            [['sites_id'], 'required'],
            [['name_daemons'], 'string', 'max' => 255],
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
            'name' => 'Название ошибки',
            'name_daemons' => 'Название демона',
            'goods_id' => 'ID товара',
            'sites_id' => 'Название сайта',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSites()
    {
        return $this->hasOne(Sites::className(), ['id' => 'sites_id']);
    }
}
