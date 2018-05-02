<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MarkUpGoods */

$this->title = 'Создать наценку на товары';
$this->params['breadcrumbs'][] = ['label' => 'Наценка на товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mark-up-goods-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
