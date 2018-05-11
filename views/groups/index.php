<?php

use app\models\CategoriesImkuh;
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
    <?php Pjax::begin(); ?>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function($data){

                    return StringHelper::truncate($data->name , 50);

                }
            ],
            //'created_at',
            [
                'attribute' => 'url_group',
                'format' => 'raw',
                'value' => function($data){

                    return Html::a(StringHelper::truncate($data->url_group,50) ,$data->url_group,[
                        'title' => 'Смелей, вперед!',
                        'target' => '_blank'
                    ]);

                }
            ],
            [
                'attribute' => 'categories_holodbar_id',
                'format' => 'raw',
                'value' => function($data){

                    $catHolod = CategoriesImkuh::find()->where(['pgid' => $data->categories_holodbar_id])->asArray()->one();

                    return Html::a(StringHelper::truncate($catHolod['name'], 50), 'http://www.holodbar.ru/content/katalog/' . $catHolod['pmgid'] . '/' . $data->categories_holodbar_id . '/',[
                        'title' => 'Смелей, вперед!',
                        'target' => '_blank'
                    ]);

                }
            ],
            [
                'attribute' => 'categories_imkuh_id',
                'format' => 'raw',
                'value' => function($data){

                    $catImkuh = CategoriesImkuh::find()->where(['pgid' => $data->categories_imkuh_id])->asArray()->one();

                    return Html::a(StringHelper::truncate($catImkuh['name'],50), 'http://www.imkuh.ru/group/'. $data->categories_imkuh_id .'/',[
                        'title' => 'Смелей, вперед!',
                        'target' => '_blank'
                    ]);

                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
