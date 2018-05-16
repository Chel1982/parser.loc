<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\LogsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Logs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logs-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php Pjax::begin() ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'firstPageLabel' => 'Первая',
            'lastPageLabel' => 'Последняя',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name:ntext',
            'description:ntext',
            'desc_add:ntext',
            'image:ntext',
            'price:ntext',
            'manufactured:ntext',
            'prod_attr:ntext',
            'availability:ntext',
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

    <?php Pjax::end() ?>

</div>
