<?php

namespace app\components\validators;

use app\models\MarkUpGoods;
use yii\validators\Validator;

class MarkFromValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if (isset($model->attributes['id'])){

            $markUp = MarkUpGoods::find()->where(['groups_id' => $model->attributes['groups_id']])->andWhere(['not in', 'id', $model->attributes['id']])->asArray()->all();

            foreach ($markUp as $markFrom){
                if ( $model->attributes['from_value'] >= $markFrom['from_value'] && $model->attributes['from_value'] <= $markFrom['to_value']){
                    $this->addError($model, 'from_value', 'Порог уже задан');
                }
            }


        }else{

            $markUp = MarkUpGoods::find()->where(['groups_id' => $model->attributes['groups_id']])->asArray()->all();

            foreach ($markUp as $markFrom){
                if ( $model->attributes['from_value'] >= $markFrom['from_value'] && $model->attributes['from_value'] <= $markFrom['to_value']){
                    $this->addError($model, 'from_value', 'Порог уже задан');
                }
            }
        }



    }
}