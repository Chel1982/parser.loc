<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "goods_has_groups".
 *
 * @property int $goods_id
 * @property int $groups_id
 *
 * @property Goods $goods
 * @property Groups $groups
 */
class GoodsHasGroups extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_has_groups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'groups_id'], 'required'],
            [['goods_id', 'groups_id'], 'integer'],
            [['goods_id', 'groups_id'], 'unique', 'targetAttribute' => ['goods_id', 'groups_id']],
            [['goods_id'], 'exist', 'skipOnError' => true, 'targetClass' => Goods::className(), 'targetAttribute' => ['goods_id' => 'id']],
            [['groups_id'], 'exist', 'skipOnError' => true, 'targetClass' => Groups::className(), 'targetAttribute' => ['groups_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'goods_id' => 'Goods ID',
            'groups_id' => 'Groups ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasOne(Groups::className(), ['id' => 'groups_id']);
    }
}
