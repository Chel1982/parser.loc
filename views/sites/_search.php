<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\SitesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sites-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'url') ?>

    <?= $form->field($model, 'down_url') ?>

    <?= $form->field($model, 'delay_parsing') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'usleep_start') ?>

    <?php // echo $form->field($model, 'usleep_stop') ?>

    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Сброс', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
