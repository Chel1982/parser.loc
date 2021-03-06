<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SitesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сайты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sites-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать сайт', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
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
            [
                'attribute' => 'delay_parsing',
                'header' => 'Задержка<br>crawler\'a, сек',

            ],
            [
                'attribute' => 'usleep_start',
                'header' => 'Нач.задер.<br>парсинга, сек',

            ],
            [
                'attribute' => 'usleep_stop',
                'header' => 'Кон.задер.<br>парсинга, сек',

            ],
            'status_cat_price:boolean',
            'status_price:boolean',
            'status_manuf:boolean',
            'status:boolean',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
