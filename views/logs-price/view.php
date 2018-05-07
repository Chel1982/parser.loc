<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\LogsPrice */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Logs Prices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logs-price-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
           // 'id',
            'log',
            'goods_id',
        ],
    ]) ?>

</div>
