<?php

use app\models\Sites;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\GoodsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goods-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name_goods') ?>

    <?= $form->field($model, 'uri_goods') ?>

    <?= $form->field($model, 'sites_id')->dropDownList(ArrayHelper::map(Sites::find()->all(),'id','name'), ['prompt' => 'Выберите сайт...']) ?>

    <?= $form->field($model, 'groups_name') ?>

    <?= $form->field($model, 'manufacturers_name') ?>

    <?= $form->field($model, 'price_from') ?>

    <?= $form->field($model, 'price_to') ?>

    <?= DatePicker::widget([
        'model' => $model,
        'attribute' => 'from_date',
        'attribute2' => 'to_date',
        'options' => ['placeholder' => 'Дата начала поиска'],
        'options2' => ['placeholder' => 'Дата окончания поиска'],
        'type' => DatePicker::TYPE_RANGE,
        'form' => $form,
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'autoclose' => true,
        ]
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Сброс', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
