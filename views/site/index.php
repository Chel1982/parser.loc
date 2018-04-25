<?php
/* @var $this yii\web\View */

use app\assets\AppAsset;
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
</div>

<?php if (isset($resСompareImkush)): ?>

    <?= Html::tag('h4', 'Синхронизированно с Imkush') ?>


    <?= Html::tag('p', 'Проверено категорий товаров: ' . '<b>'. $resСompareImkush['count'] .'</b>') ?>

    <?php if (isset($resСompareImkush['change'])): ?>
        <?= Html::tag('p', 'Измененных категорий товаров: ' . '<b>'. $resСompareImkush['change'] .'</b>') ?>
    <?php endif; ?>

    <?php if (isset($resСompareImkush['add']) and $resСompareImkush['add'] != 0): ?>
        <?= Html::tag('p', 'Новых категорий товаров: ' . '<b>'. $resСompareImkush['add'] .'</b>') ?>
    <?php endif; ?>

    <?php if (isset($resСompareImkush['delete']) and $resСompareImkush['delete'] != 0): ?>
        <?= Html::tag('p', 'Удаленных категорий товаров: ' . '<b>'. $resСompareImkush['delete'] .'</b>') ?>
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