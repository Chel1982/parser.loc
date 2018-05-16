<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LogsPriceAvail */

$this->title = 'Update Logs Price Avail: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Logs Price Avails', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="logs-price-avail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
