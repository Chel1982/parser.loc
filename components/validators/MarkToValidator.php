<?php

namespace app\components\validators;

use app\models\MarkUpGoods;
use yii\validators\Validator;

class MarkToValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if (isset($model->attributes['id'])){

            $markUp = MarkUpGoods::find()->where(['groups_id' => $model->attributes['groups_id']])->andWhere(['not in', 'id', $model->attributes['id']])->asArray()->all();

            foreach ($markUp as $markTo){

                if ( $model->attributes['to_value'] >= $markTo['from_value'] && $model->attributes['to_value'] <= $markTo['to_value']){
                    $this->addError($model, 'to_value', 'Порог уже задан');
                }

                if($model->attributes['from_value'] < $markTo['from_value'] && $model->attributes['to_value'] > $markTo['from_value']){
                    $this->addError($model, 'to_value', 'Порог превышает максимальное значение' . $markTo['from_value']);
                }


            }
        }else{

            $markUp = MarkUpGoods::find()->where(['groups_id' => $model->attributes['groups_id']])->asArray()->all();

            foreach ($markUp as $markTo){
                if ( $model->attributes['to_value'] >= $markTo['from_value'] && $model->attributes['to_value'] <= $markTo['to_value']){
                    $this->addError($model, 'to_value', 'Порог уже задан');
                }
                if($model->attributes['from_value'] < $markTo['from_value'] && $model->attributes['to_value'] > $markTo['from_value']){
                    $this->addError($model, 'to_value', 'Порог превышает максимальное значение');
                }
            }
        }

    }
}