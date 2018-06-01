<?php
/* @var $this yii\web\View */

use app\assets\AppAsset;
use app\models\ProductGroupsHolodbar;
use app\models\ProductsMainGroupsHolodbar;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Parser API';
AppAsset::register($this);
?>

<div class="form" style="display: flex">
    <?php ActiveForm::begin(); ?>

        <div class="form-group">

            <?= Html::input('hidden','imkush','0') ?>

            <?= Html::submitButton('Синхронизировать категории товаров imkush', ['class' => 'btn btn-success imkush']) ?>

        </div>

    <?php ActiveForm::end(); ?>

    <?php ActiveForm::begin(); ?>

        <div class="form-group" style="margin-left: 10px">

            <?= Html::input('hidden','holodbar','0') ?>

            <?= Html::submitButton('Синхронизировать категории товаров holodbar', ['class' => 'btn btn-success holodbar']) ?>

        </div>

    <?php ActiveForm::end(); ?>

    <?php ActiveForm::begin(); ?>

        <div class="form-group" style="margin-left: 10px">

            <?= Html::input('hidden','mark_up_price','0') ?>

            <?= Html::submitButton('Применить все наценки', ['class' => 'btn btn-success mark_up_price']) ?>

        </div>

    <?php ActiveForm::end(); ?>

    <?php ActiveForm::begin(); ?>

        <div class="form-group" style="margin-left: 10px">

            <?= Html::input('hidden','export','0') ?>

            <?= Html::submitButton('Применить экспорт', ['class' => 'btn btn-success export']) ?>

        </div>

    <?php ActiveForm::end(); ?>
</div>

<?php if (isset($resСompareImkuh)): ?>

    <?= Html::tag('h4', 'Синхронизированно с Imkush') ?>


    <?= Html::tag('p', 'Проверено категорий товаров: ' . '<b>'. $resСompareImkuh['count'] .'</b>') ?>

    <?php if (isset($resСompareImkuh['change'])): ?>
        <?= Html::tag('p', 'Измененных категорий товаров: ' . '<b>'. $resСompareImkuh['change'] .'</b>') ?>
    <?php endif; ?>

    <?php if (isset($resСompareImkuh['add']) and $resСompareImkuh['add'] != 0): ?>
        <?= Html::tag('p', 'Новых категорий товаров: ' . '<b>'. $resСompareImkuh['add'] .'</b>') ?>
    <?php endif; ?>

    <?php if (isset($resСompareImkuh['delete']) and $resСompareImkuh['delete'] != 0): ?>
        <?= Html::tag('p', 'Удаленных категорий товаров: ' . '<b>'. $resСompareImkuh['delete'] .'</b>') ?>
    <?php endif; ?>

<?php endif; ?>

<?php if (isset($resСompareHolodbar)): ?>

    <?= Html::tag('h4', 'Синхронизированно с Holodbar') ?>


    <?= Html::tag('p', 'Проверено категорий товаров: ' . '<b>'. $resСompareHolodbar['count'] .'</b>') ?>

    <?php if (isset($resСompareHolodbar['change'])): ?>
        <?= Html::tag('p', 'Измененных категорий товаров: ' . '<b>'. $resСompareHolodbar['change'] .'</b>') ?>
    <?php endif; ?>

    <?php if (isset($resСompareHolodbar['add']) and $resСompareHolodbar['add'] != 0): ?>
        <?= Html::tag('p', 'Новых категорий товаров: ' . '<b>'. $resСompareHolodbar['add'] .'</b>') ?>
    <?php endif; ?>

    <?php if (isset($resСompareHolodbar['delete']) and $resСompareHolodbar['delete'] != 0): ?>
        <?= Html::tag('p', 'Удаленных категорий товаров: ' . '<b>'. $resСompareHolodbar['delete'] .'</b>') ?>
    <?php endif; ?>

<?php endif; ?>

<?php if (isset($resMarkUp)): ?>
    <?= '<h4>' . $resMarkUp . '</h4>'?>
<?php endif; ?>
