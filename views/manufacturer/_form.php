<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Manufacturer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="manufacturer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'disabled' => 'disabled']) ?>

    <?= $form->field($model, 'sites_url')->textInput(['maxlength' => true, 'disabled' => 'disabled']) ?>

    <?= $form->field($model, 'imkuh')->textInput()->dropDownList([1 => 'Да', 0 => 'Нет']) ?>

    <?= $form->field($model, 'holodbar')->textInput()->dropDownList([1 => 'Да', 0 => 'Нет']) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
