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

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'mark_up_price')->textInput() ?>

    <?= $form->field($model, 'availability')->dropDownList([1 => 'Да', 0 => 'Нет']) ?>

    <?php if($model->descriptions !== null and $model->descriptions->main !== null): ?>
        <?= $form->field($model->descriptions, 'main')->textarea(['rows' => '6']) ?>
    <?php endif; ?>

    <?php if($model->descriptions !== null and $model->descriptions->additional !== null): ?>
        <?= $form->field($model->descriptions, 'additional')->textarea(['rows' => '6']) ?>
    <?php endif; ?>

    <?= $form->field($model, 'manufacturer')->textInput() ?>

    <?php if($model->productAttributes !== null): ?>
        <?= $form->field($model->productAttributes, 'content')->textarea(['rows' => '6']) ?>
    <?php endif; ?>

    <?= $form->field($model, 'sites_id')->dropDownList([ArrayHelper::map(Sites::find()->all(), 'id', 'name')]) ?>

    <?= $form->field($model, 'groups_id')->dropDownList(ArrayHelper::map(Groups::find()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?php if($model->images !== null): ?>

        <b> Изображение продукции </b> <br>

        <div class="image_goods__items">
            <?php foreach ($model->images as $image): ?>
                <div class="image_goods">
                    <?= Html::img('@web/uploads/images/' . $model->id . '/' . $image->name, ['alt' => $image->name, 'class' => 'image-goods']); ?>
                    <?= Html::a('Удалить', ['delete-image', 'id' => $model->id, 'idImage' => $image->id,'name' => $image->name], ['class' => 'btn btn-danger']) ?>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
