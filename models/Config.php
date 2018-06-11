<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "config".
 *
 * @property int $id
 * @property string $alias
 * @property string $value
 */
class Config extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alias' => 'Алиас',
            'value' => 'Значение',
        ];
    }
}
