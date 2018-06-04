<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "groups".
 *
 * @property int $id
 * @property string $name
 * @property string $created_at
 * @property string $url_group
 * @property int $categories_holodbar_id
 * @property int $categories_imkuh_id
 *
 * @property Goods[] $goods
 * @property CategoriesHolodbar $categoriesHolodbar
 * @property CategoriesImkuh $categoriesImkuh
 * @property MarkUpGoods[] $markUpGoods
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
            [['categories_holodbar_id', 'categories_imkuh_id'], 'integer'],
            [['name', 'url_group'], 'string', 'max' => 255],
            [['categories_holodbar_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoriesHolodbar::class, 'targetAttribute' => ['categories_holodbar_id' => 'pgid']],
            [['categories_imkuh_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoriesImkuh::class, 'targetAttribute' => ['categories_imkuh_id' => 'pgid']],
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
            'categories_holodbar_id' => 'Группы товаров Holodbar',
            'categories_imkuh_id' => 'Группы товаров Imkuh',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasMany(Goods::class, ['groups_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesHolodbar()
    {
        return $this->hasOne(CategoriesHolodbar::class, ['pgid' => 'categories_holodbar_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesImkuh()
    {
        return $this->hasOne(CategoriesImkuh::class, ['pgid' => 'categories_imkuh_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMarkUpGoods()
    {
        return $this->hasMany(MarkUpGoods::class, ['groups_id' => 'id']);
    }

    public function getHierarchyHolod() {

        $parents = ProductsMainGroupsHolodbar::find()->asArray()->all();

        foreach($parents as $id => $p) {
            $children = ProductGroupsHolodbar::find()->where(['pmgid' => $p['pmgid']])->orderBy(['name' => SORT_ASC])->asArray()->all();
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
            $children = ProductGroupsImkuh::find()->where(['pmgid' => $p['pmgid']])->orderBy(['name' => SORT_ASC])->asArray()->all();
            $child_options = [];
            foreach($children as $child) {
                $child_options[$child['pgid']] = $child['name'];
            }
            $options[$p['name']] = $child_options;
        }
        return $options;
    }
}
