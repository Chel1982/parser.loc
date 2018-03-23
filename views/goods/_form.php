<?php

use app\models\Groups;
use app\models\Sites;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Goods */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goods-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name_goods')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'uri_goods')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'sites_id')->dropDownList(ArrayHelper::map(Sites::find()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'groups_id')->dropDownList(ArrayHelper::map(Groups::find()->all(), 'id', 'name')) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
