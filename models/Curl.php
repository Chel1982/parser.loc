<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "curl".
 *
 * @property int $id
 * @property string $key
 * @property string $value
 * @property int $status
 * @property int $sites_id
 *
 * @property Sites $sites
 */
class Curl extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'curl';
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
            [['status'], 'string', 'max' => 1],
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
            'key' => 'Key',
            'value' => 'Value',
            'status' => 'Status',
            'sites_id' => 'Sites ID',
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
