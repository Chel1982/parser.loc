<?php

use kartik\export\ExportMenu;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\GoodsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Скачанные товары';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="goods-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php

    echo ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'id',
            'name_goods',
            'uri_goods',
            'sites.name',
            'groups.name',
            'descriptions.main',
            'descriptions.additional',
            'images.name',
            'price',
            'manufacturers.name',
            'productAttributes.content',
            'created_at',

        ]
    ]);
    ?>

    <?php Pjax::begin() ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'firstPageLabel' => 'Первая',
            'lastPageLabel' => 'Последняя',
        ],

        'columns' => [

            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'name_goods',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a(
                        $data->name_goods,
                        $data->uri_goods,
                        [
                            'title' => 'Смелей, вперед!',
                            'target' => '_blank'
                        ]
                    );
                }
            ],
            [
                'attribute' => 'price',
                'value' => function ($model) {
                    if (isset($model->price)){
                        return $model->price . ' ' . $model->currency;
                    }
                    return '';
                },
            ],
            [
                'attribute' => 'mark_up_price',
                'value' => function ($model) {
                    if (isset($model->mark_up_price)){
                        return $model->mark_up_price . ' ' . $model->currency;
                    }
                    return '';
                },
            ],
            [
                'attribute' => 'sites_id',
                'value' => function ($model) {
                    return $model->sites->name;
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end() ?>

</div>
