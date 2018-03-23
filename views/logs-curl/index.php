<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\LogsCurlSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Logs Curls';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logs-curl-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Logs Curl', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name:ntext',
            'name_daemons',
            'goods_id',
            [
                'attribute' => 'sites_id',
                'value' => function ($model) {
                    return StringHelper::truncate($model->sites->name, 35);
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
