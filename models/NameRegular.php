<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "name_regular".
 *
 * @property int $id
 * @property string $name
 *
 * @property Xpath[] $xpaths
 */
class NameRegular extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'name_regular';
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
    public function getXpaths()
    {
        return $this->hasMany(Xpath::className(), ['name_regular_id' => 'id']);
    }
}
