<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "groups".
 *
 * @property int $id
 * @property string $name
 * @property string $from_date
 * @property string $to_date
 * @property string $created_at
 * @property string $url_group
 *
 * @property Goods[] $goods
 */
class Groups extends \yii\db\ActiveRecord
{
    public $from_date;
    public $to_date;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'groups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['name', 'url_group'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID группы',
            'name' => 'Название группы',
            'created_at' => 'Дата создания',
            'url_group' => 'Url группы',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasMany(Goods::className(), ['groups_id' => 'id']);
    }
}
