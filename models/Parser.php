<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "parser".
 *
 * @property int $id
 * @property string $name
 *
 * @property XpathParse[] $xpathParses
 */
class Parser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parser';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 45],
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
    public function getXpathParses()
    {
        return $this->hasMany(XpathParse::className(), ['parser_id' => 'id']);
    }
}
