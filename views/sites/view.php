<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sites */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Сайты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sites-view">

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
            'name',
            [
                'attribute' => 'url',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a(
                        $data->url,
                        $data->url,
                        [
                            'title' => 'Смелей, вперед!',
                            'target' => '_blank'
                        ]
                    );
                }
            ],
            [
                'attribute' => 'down_url',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a(
                        $data->down_url,
                        $data->down_url,
                        [
                            'title' => 'Смелей, вперед!',
                            'target' => '_blank'
                        ]
                    );
                }
            ],
            'delay_parsing',
            'usleep_start',
            'usleep_stop',
            'status:boolean',
            'status_price:boolean',
        ],
    ]) ?>

</div>
