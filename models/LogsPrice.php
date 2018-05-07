<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "logs_price".
 *
 * @property int $id
 * @property string $log
 * @property int $goods_id
 *
 * @property Goods $goods
 */
class LogsPrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'logs_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id'], 'required'],
            [['goods_id'], 'integer'],
            [['log'], 'string', 'max' => 45],
            [['goods_id'], 'exist', 'skipOnError' => true, 'targetClass' => Goods::className(), 'targetAttribute' => ['goods_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'log' => 'Logs',
            'goods_id' => 'ID товара',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }
}
