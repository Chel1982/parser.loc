<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "curl_auth".
 *
 * @property int $id
 * @property string $key
 * @property string $value
 * @property int $sites_id
 *
 * @property Sites $sites
 */
class CurlAuth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'curl_auth';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'value'], 'string'],
            [['sites_id'], 'required'],
            [['sites_id'], 'integer'],
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
            'key' => 'Ключ',
            'value' => 'Значение',
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
