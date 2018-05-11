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

    <?= $form->field($model, 'categories_holodbar_id')->dropDownList($model->getHierarchyHolod(),['prompt' => '', 'class'=>'form-control required']) ?>

    <?= $form->field($model, 'categories_imkuh_id')->dropDownList($model->getHierarchyImkuh(),['prompt' => '', 'class'=>'form-control required']) ?>

    <div class="form-group">
        <?= Html::submitButton('Связать', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
