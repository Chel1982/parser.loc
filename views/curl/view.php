<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Curl */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Curls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="curl-view">

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
            'key:ntext',
            'value:ntext',
            [
                'attribute' => 'sites_id',
                'value' => function ($model) {
                    return $model->sites->name;
                },
            ],
        ],
    ]) ?>

</div>
