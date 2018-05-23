<?php

namespace app\components\validators;

use yii\validators\Validator;

class CompareValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {

        if ($model->attributes['to_value'] <= $model->attributes['from_value']){
            $this->addError($model, 'to_value', 'Значение (от) должно быть меньше значения (до)');
        }

    }
}