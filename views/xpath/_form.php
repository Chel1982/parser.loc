<?php

use app\models\NameRegular;
use app\models\Sites;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Xpath */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="xpath-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sites_id')->dropDownList(ArrayHelper::map(Sites::find()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'regular')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_regular_id')->dropDownList(ArrayHelper::map(NameRegular::find()->all(), 'id', 'name')) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
