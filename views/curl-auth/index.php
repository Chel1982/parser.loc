<?php

use app\models\Sites;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CurlAuthSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Curl Auths';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="curl-auth-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Curl Auth', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

          //  'id',
            'key:ntext',
            'value:ntext',
            [
                'attribute' => 'sites_id',
                'value' => function ($model) {
                    return $model->sites->name;
                },
                'filter' => ArrayHelper::map(Sites::find()->all(), 'id', 'name'),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
