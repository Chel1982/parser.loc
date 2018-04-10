<?php

use kartik\export\ExportMenu;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\StringHelper;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\GroupsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Группы товаров';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="groups-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php

    $gridColumn = [
        'id',
        'name',
        'created_at',
        'url_group:url',
    ];

    echo ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumn,
    ]);

    ?>

    <?php Pjax::begin() ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'pager' => [
            'firstPageLabel' => 'Первая',
            'lastPageLabel' => 'Последняя',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            [
                'attribute' => 'url_group',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a(
                        $data->url_group,
                        $data->url_group,
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
