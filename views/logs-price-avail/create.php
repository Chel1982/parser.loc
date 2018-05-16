<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LogsPriceAvail */

$this->title = 'Create Logs Price Avail';
$this->params['breadcrumbs'][] = ['label' => 'Logs Price Avails', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logs-price-avail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
