<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ExchangeRates */

$this->title = 'Создать Курс валют';
$this->params['breadcrumbs'][] = ['label' => 'Курс валют', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="exchange-rates-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
