<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "demons".
 *
 * @property int $id
 * @property string $name
 *
 * @property Curl[] $curls
 */
class Demons extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'demons';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurls()
    {
        return $this->hasMany(Curl::className(), ['demons_id' => 'id']);
    }
}
