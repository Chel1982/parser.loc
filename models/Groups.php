<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "groups".
 *
 * @property int $id
 * @property string $name
 * @property string $created_at
 * @property string $from_date
 * @property string $to_date
 * @property string $url_group
 * @property int $cat_holod
 * @property int $cat_imkuh
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
            [['cat_holod', 'cat_imkuh'], 'integer'],
            [['name', 'url_group'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название группы',
            'created_at' => 'Дата создания',
            'url_group' => 'Url группы',
            'cat_holod' => 'Категория Holodbar',
            'cat_imkuh' => 'Категория Imkuh',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasMany(Goods::className(), ['groups_id' => 'id']);
    }

    public function getHierarchyHolod() {

        $parents = ProductsMainGroupsHolodbar::find()->asArray()->all();

        foreach($parents as $id => $p) {
            $children = ProductGroupsHolodbar::find()->where(['pmgid' => $p['pmgid']])->asArray()->all();
            $child_options = [];
            foreach($children as $child) {
                $child_options[$child['pgid']] = $child['name'];
            }
            $options[$p['name']] = $child_options;
        }
        return $options;
    }

    public function getHierarchyImkuh() {

        $parents = ProductsMainGroupsImkuh::find()->asArray()->all();

        foreach($parents as $id => $p) {
            $children = ProductGroupsImkuh::find()->where(['pmgid' => $p['pmgid']])->asArray()->all();
            $child_options = [];
            foreach($children as $child) {
                $child_options[$child['pgid']] = $child['name'];
            }
            $options[$p['name']] = $child_options;
        }
        return $options;
    }
}
