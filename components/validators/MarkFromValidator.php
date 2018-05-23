<?php

namespace app\components\validators;

use app\models\MarkUpGoods;
use yii\validators\Validator;

class MarkFromValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if (isset($model->attributes['id'])){

            if (MarkUpGoods::find()->where(['categories_imkuh_id' => $model->attributes['categories_imkuh_id']])->andWhere(['not in', 'id', $model->attributes['id']])->exists()){

                $markUp = MarkUpGoods::find()->where(['categories_imkuh_id' => $model->attributes['categories_imkuh_id']])->andWhere(['not in', 'id', $model->attributes['id']])->asArray()->all();

                foreach ($markUp as $markFrom){
                    if ( $model->attributes['from_value'] >= $markFrom['from_value'] && $model->attributes['from_value'] <= $markFrom['to_value']){
                        $this->addError($model, 'from_value', 'Порог уже задан для групп товаров Imkuh');
                    }
                }
            }

            if (MarkUpGoods::find()->where(['categories_holodbar_id' => $model->attributes['categories_holodbar_id']])->andWhere(['not in', 'id', $model->attributes['id']])->exists()){

                $markUp = MarkUpGoods::find()->where(['categories_holodbar_id' => $model->attributes['categories_holodbar_id']])->andWhere(['not in', 'id', $model->attributes['id']])->asArray()->all();

                foreach ($markUp as $markFrom){
                    if ( $model->attributes['from_value'] >= $markFrom['from_value'] && $model->attributes['from_value'] <= $markFrom['to_value']){
                        $this->addError($model, 'from_value', 'Порог уже задан для групп товаров Holodbar');
                    }
                }
            }

            if (MarkUpGoods::find()->where(['manufacturer_id_imkuh' => $model->attributes['manufacturer_id_imkuh']])->andWhere(['not in', 'id', $model->attributes['id']])->exists()){

                $markUp = MarkUpGoods::find()->where(['manufacturer_id_imkuh' => $model->attributes['manufacturer_id_imkuh']])->andWhere(['not in', 'id', $model->attributes['id']])->asArray()->all();

                foreach ($markUp as $markFrom){
                    if ( $model->attributes['from_value'] >= $markFrom['from_value'] && $model->attributes['from_value'] <= $markFrom['to_value']){
                        $this->addError($model, 'from_value', 'Порог уже задан производителя для Imkuh');
                    }
                }
            }

            if (MarkUpGoods::find()->where(['manufacturer_id_holodbar' => $model->attributes['manufacturer_id_holodbar']])->andWhere(['not in', 'id', $model->attributes['id']])->exists()){

                $markUp = MarkUpGoods::find()->where(['manufacturer_id_holodbar' => $model->attributes['manufacturer_id_holodbar']])->andWhere(['not in', 'id', $model->attributes['id']])->asArray()->all();

                foreach ($markUp as $markFrom){
                    if ( $model->attributes['from_value'] >= $markFrom['from_value'] && $model->attributes['from_value'] <= $markFrom['to_value']){
                        $this->addError($model, 'from_value', 'Порог уже задан производителя для Holodbar');
                    }
                }
            }

        }else{

            if (MarkUpGoods::find()->where(['categories_imkuh_id' => $model->attributes['categories_imkuh_id']])->exists()){

                $markUp = MarkUpGoods::find()->where(['categories_imkuh_id' => $model->attributes['categories_imkuh_id']])->asArray()->all();

                foreach ($markUp as $markFrom){
                    if ( $model->attributes['from_value'] >= $markFrom['from_value'] && $model->attributes['from_value'] <= $markFrom['to_value']){
                        $this->addError($model, 'from_value', 'Порог уже задан для групп товаров Imkuh');
                    }
                }
            }

            if (MarkUpGoods::find()->where(['categories_holodbar_id' => $model->attributes['categories_holodbar_id']])->exists()){

                $markUp = MarkUpGoods::find()->where(['categories_holodbar_id' => $model->attributes['categories_holodbar_id']])->asArray()->all();

                foreach ($markUp as $markFrom){
                    if ( $model->attributes['from_value'] >= $markFrom['from_value'] && $model->attributes['from_value'] <= $markFrom['to_value']){
                        $this->addError($model, 'from_value', 'Порог уже задан для групп товаров Holodbar');
                    }
                }
            }

            if (MarkUpGoods::find()->where(['manufacturer_id_imkuh' => $model->attributes['manufacturer_id_imkuh']])->exists()){

                $markUp = MarkUpGoods::find()->where(['manufacturer_id_imkuh' => $model->attributes['manufacturer_id_imkuh']])->asArray()->all();

                foreach ($markUp as $markFrom){
                    if ( $model->attributes['from_value'] >= $markFrom['from_value'] && $model->attributes['from_value'] <= $markFrom['to_value']){
                        $this->addError($model, 'from_value', 'Порог уже задан производителя для Imkuh');
                    }
                }
            }

            if (MarkUpGoods::find()->where(['manufacturer_id_holodbar' => $model->attributes['manufacturer_id_holodbar']])->exists()){

                $markUp = MarkUpGoods::find()->where(['manufacturer_id_holodbar' => $model->attributes['manufacturer_id_holodbar']])->asArray()->all();

                foreach ($markUp as $markFrom){
                    if ( $model->attributes['from_value'] >= $markFrom['from_value'] && $model->attributes['from_value'] <= $markFrom['to_value']){
                        $this->addError($model, 'from_value', 'Порог уже задан производителя для Holodbar');
                    }
                }
            }
        }
    }
}