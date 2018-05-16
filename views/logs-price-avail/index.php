<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\LogsPriceAvailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Logs цены и наличия товаров';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logs-price-avail-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
            'price',
            'availability',
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
