<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sites".
 *
 * @property int $id
 * @property string $name
 * @property string $url
 * @property string $down_url
 * @property int $queue
 * @property int $status
 * @property int $usleep_start
 * @property int $usleep_stop
 *
 * @property Curl[] $curls
 * @property CurlAuth[] $curlAuths
 * @property Goods[] $goods
 * @property Xpath[] $xpaths
 */
class Sites extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sites';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['queue', 'usleep_start', 'usleep_stop'], 'integer'],
            [['name', 'url', 'down_url'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 1],
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
            'url' => 'Url',
            'down_url' => 'Down Url',
            'queue' => 'Queue',
            'status' => 'Status',
            'usleep_start' => 'Usleep Start',
            'usleep_stop' => 'Usleep Stop',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurls()
    {
        return $this->hasMany(Curl::className(), ['sites_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurlAuths()
    {
        return $this->hasMany(CurlAuth::className(), ['sites_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasMany(Goods::className(), ['sites_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXpaths()
    {
        return $this->hasMany(Xpath::className(), ['sites_id' => 'id']);
    }
}
