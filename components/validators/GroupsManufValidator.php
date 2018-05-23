<?php

namespace app\components\validators;

use yii\validators\Validator;

class GroupsManufValidator extends Validator
{
    public function validateAttributes($model, $attributes = NULL)
    {
        if ($model->attributes['categories_imkuh_id'] == '' && $model->attributes['categories_holodbar_id'] == ''  && $model->attributes['manufacturer_id_imkuh'] == '' && $model->attributes['manufacturer_id_holodbar'] == ''){
            $this->addError($model, 'categories_imkuh_id', 'Задайте хотя бы одно значение');
            $this->addError($model, 'categories_holodbar_id', 'Задайте хотя бы одно значение');
            $this->addError($model, 'manufacturer_id_imkuh', 'Задайте хотя бы одно значение');
            $this->addError($model, 'manufacturer_id_holodbar', 'Задайте хотя бы одно значение');
        }
    }

}