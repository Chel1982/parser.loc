<?php

namespace app\models;

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
 * @property int $groups_id
 *
 * @property Groups $groups
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
            [['price_value', 'from_value', 'to_value', 'groups_id'], 'integer'],
            [['groups_id'], 'required'],
            [['percent', 'absolute'], 'string', 'max' => 1],
            [['groups_id'], 'exist', 'skipOnError' => true, 'targetClass' => Groups::className(), 'targetAttribute' => ['groups_id' => 'id']],
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
            'groups_id' => 'Название группы',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasOne(Groups::className(), ['id' => 'groups_id']);
    }
}
