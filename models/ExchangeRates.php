<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "exchange_rates".
 *
 * @property int $id
 * @property double $dollar
 * @property double $euro
 */
class ExchangeRates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'exchange_rates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dollar', 'euro'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dollar' => 'Доллар',
            'euro' => 'Евро',
        ];
    }
}
