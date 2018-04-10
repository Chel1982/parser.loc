<?php

use app\models\Sites;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\LogsCurlSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Logs Curls';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logs-curl-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name:ntext',
            'goods_id',
            [
                'attribute' => 'sites_id',
                'value' => function ($model) {
                    return $model->sites->name;
                },
                'filter' => ArrayHelper::map(Sites::find()->all(), 'id', 'name'),
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view} {delete}',

            ],
        ],
    ]); ?>
</div>
