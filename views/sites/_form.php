<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sites */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sites-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'down_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'delay_parsing')->textInput() ?>

    <?= $form->field($model, 'usleep_start')->textInput() ?>

    <?= $form->field($model, 'usleep_stop')->textInput() ?>

    <?= $form->field($model, 'status_price')->dropDownList([1 => 'Да', 0 => 'Нет']) ?>

    <?= $form->field($model, 'status_cat_price')->dropDownList([1 => 'Да', 0 => 'Нет']) ?>

    <?= $form->field($model, 'status_manuf')->dropDownList([1 => 'Да', 0 => 'Нет']) ?>

    <?= $form->field($model, 'status')->dropDownList([1 => 'Да', 0 => 'Нет']) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
