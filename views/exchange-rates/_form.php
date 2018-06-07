<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ExchangeRates */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="exchange-rates-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dollar')->textInput() ?>

    <?= $form->field($model, 'euro')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
