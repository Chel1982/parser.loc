<?php

use app\models\CategoriesHolodbar;
use app\models\CategoriesImkuh;
use app\models\Groups;
use app\models\Manufacturer;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MarkUpGoods */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mark-up-goods-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'price_value')->textInput()->textInput(['width' => '50px']) ?>

    <?= $form->field($model, 'percent')->checkbox() ?>

    <?= $form->field($model, 'absolute')->checkbox() ?>

    <?= $form->field($model, 'from_value')->textInput() ?>

    <?= $form->field($model, 'to_value')->textInput() ?>

    <?= $form->field($model, 'categories_imkuh_id')->dropDownList(ArrayHelper::map(CategoriesImkuh::findAll(Groups::find()->where(['is not', 'categories_imkuh_id', NULL])->select('categories_imkuh_id')), 'pgid', 'name'), ['prompt' => 'Выберите группу Imkuh'])->label('Группа товаров Imkuh') ?>

    <?= $form->field($model, 'categories_holodbar_id')->dropDownList(ArrayHelper::map(CategoriesHolodbar::findAll(Groups::find()->where(['is not', 'categories_holodbar_id', NULL])->select('categories_holodbar_id')), 'pgid', 'name'), ['prompt' => 'Выберите группу Holodbar'])->label('Группа товаров Holodbar') ?>

    <?= $form->field($model, 'manufacturer_id_imkuh')->dropDownList(ArrayHelper::map(Manufacturer::find()->where(['imkuh' => 1])->all(), 'id', 'name'), ['prompt' => 'Выберите производителя для Imkuh'])->label('Производители для Imkuh') ?>

    <?= $form->field($model, 'manufacturer_id_holodbar')->dropDownList(ArrayHelper::map(Manufacturer::find()->where(['holodbar' => 1])->all(), 'id', 'name'), ['prompt' => 'Выберите производителя для Holodbar'])->label('Производители для Holodbar') ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
