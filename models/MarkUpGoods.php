<?php

namespace app\models;

use app\components\validators\MarkFromValidator;
use app\components\validators\MarkToValidator;
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
            [['price_value', 'from_value', 'to_value', 'groups_id'], 'required'],
            [['to_value'], 'compare', 'message' => 'Значение "Порог(от)" должно быть меньше значения "Порог(до)"', 'compareAttribute' => 'from_value', 'operator' => '>'],
            [['from_value'], MarkFromValidator::class],
            [['to_value'], MarkToValidator::class],
            [['percent', 'absolute'], 'string', 'max' => 1],
            [['groups_id'], 'exist', 'skipOnError' => true, 'targetClass' => Groups::class, 'targetAttribute' => ['groups_id' => 'id']],
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
        return $this->hasOne(Groups::class, ['id' => 'groups_id']);
    }
}
