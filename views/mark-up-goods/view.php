<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MarkUpGoods */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Наценка на товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mark-up-goods-view">

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
           // 'id',
            'price_value',
            'from_value',
            'to_value',
            [
                'attribute' => 'manufacturer_id',
                'value' => function ($model) {
                    if(isset($model->manufacturer->name)){
                        return $model->manufacturer->name;
                    }else{
                        return false;
                    }
                },
            ],
            [
                'attribute' => 'groups_id',
                'value' => function ($model) {
                    if (isset($model->groups->name)){
                        return $model->groups->name;
                    }else{
                        return false;
                    }

                },
            ],
            'percent:boolean',
            'absolute:boolean',
        ],
    ]) ?>

</div>
