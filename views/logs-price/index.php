<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\LogsPriceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Logs Prices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logs-price-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
            'log',
            [
                'attribute' => 'goods_id',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a(
                        $data->goods->id,
                        $data->goods->uri_goods,
                        [
                            'title' => 'Смелей, вперед!',
                            'target' => '_blank'
                        ]
                    );
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view} {delete}',
            ],
        ],
    ]); ?>
</div>
