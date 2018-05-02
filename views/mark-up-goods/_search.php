<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\MarkUpGoodsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mark-up-goods-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'percent') ?>

    <?= $form->field($model, 'absolute') ?>

    <?= $form->field($model, 'price_value') ?>

    <?= $form->field($model, 'from_value') ?>

    <?php // echo $form->field($model, 'to_value') ?>

    <?php // echo $form->field($model, 'groups_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
