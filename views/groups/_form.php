<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Groups */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="groups-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['disabled' => 'disabled']) ?>

    <?= $form->field($model, 'created_at')->textInput(['disabled' => 'disabled']) ?>

    <?= $form->field($model, 'url_group')->textInput(['maxlength' => true, 'disabled' => 'disabled']) ?>

    <?= $form->field($model, 'cat_holod')->dropDownList($model->getHierarchyHolod(),['prompt' => 'Категории', 'class'=>'form-control required']) ?>

    <?= $form->field($model, 'cat_imkuh')->dropDownList($model->getHierarchyImkuh(),['prompt' => 'Категории', 'class'=>'form-control required']) ?>

    <div class="form-group">
        <?= Html::submitButton('Связать', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
