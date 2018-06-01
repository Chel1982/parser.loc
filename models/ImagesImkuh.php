<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
/**
 * This is the model class for table "catalog".
 *
 * @property int $iid
 * @property int $pid
 * @property string $image
 * @property int $main
 *
 */

class ImagesImkuh extends ActiveRecord {

    public static function getDb() {
        return Yii::$app->get('dbImkuh');
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'images';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'iid' => 'ID',
            'pid' => 'ID товара',
            'image' => 'Название картинки',
        ];
    }

}