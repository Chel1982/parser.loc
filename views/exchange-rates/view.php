<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ExchangeRates */

$this->title = 'Курс валют';
$this->params['breadcrumbs'][] = ['label' => 'Курс валют', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="exchange-rates-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'dollar',
            'euro',
        ],
    ]) ?>

</div>
