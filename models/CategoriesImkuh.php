<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categories_imkuh".
 *
 * @property int $id
 * @property int $pgid
 * @property int $pmgid
 * @property string $name
 */
class CategoriesImkuh extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories_imkuh';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pgid', 'pmgid'], 'integer'],
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
            'pgid' => 'Pgid',
            'pmgid' => 'Pmgid',
            'name' => 'Name',
        ];
    }
}
