<?php

use app\models\Sites;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CurlAuth */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="curl-auth-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'key')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'value')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'sites_id')->dropDownList(ArrayHelper::map(Sites::find()->all(), 'id', 'name')) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
