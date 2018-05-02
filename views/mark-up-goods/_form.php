<?php

use app\models\Groups;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MarkUpGoods */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mark-up-goods-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'price_value')->textInput()->textInput(['width' => '50px']) ?>

    <?= $form->field($model, 'percent')->checkbox() ?>

    <?= $form->field($model, 'absolute')->checkbox() ?>

    <?= $form->field($model, 'from_value')->textInput() ?>

    <?= $form->field($model, 'to_value')->textInput() ?>

    <?= $form->field($model, 'groups_id')->dropDownList(ArrayHelper::map(Groups::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name')) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
