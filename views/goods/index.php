<?php

use app\models\Groups;
use app\models\Sites;
use kartik\export\ExportMenu;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\StringHelper;
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

    <p>
        <?= Html::a('Создать товар', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
            'prices.price',
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
                'value' => function ($model) {
                    return StringHelper::truncate($model->name_goods, 35);
                }

            ],
            [
                'attribute' => 'uri_goods',
                'value' => function ($model) {
                    return StringHelper::truncate($model->uri_goods, 35);
                }

            ],
            [
                'attribute' => 'sites_id',
                'value' => function ($model) {
                    return StringHelper::truncate($model->sites->name, 35);
                },
            ],

            'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end() ?>

</div>
