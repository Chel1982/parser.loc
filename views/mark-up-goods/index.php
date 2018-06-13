<?php

use app\models\CategoriesHolodbar;
use app\models\CategoriesImkuh;
use app\models\Groups;
use app\models\Manufacturer;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\MarkUpGoodsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Наценка на товары';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mark-up-goods-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать наценку на товары', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',

            [
                'attribute' => 'price_value',
                'value' => function ($model) {

                    if ($model->percent == 1){
                        return $model->price_value . ' %';
                    }elseif ($model->absolute == 1){
                        return $model->price_value . ' RUB';
                    }else{
                        return $model->price_value;
                    }
                },
            ],
            'from_value',
            'to_value',
            [
                'attribute' => 'categories_imkuh_id',
                'value' => function ($model) {
                    if(isset($model->categoriesImkuh->name)){
                        return $model->categoriesImkuh->name;
                    }else{
                        return false;
                    }

                },
                'filter' => ArrayHelper::map(CategoriesImkuh::findAll(['pgid' => Groups::find()->where(['is not', 'categories_imkuh_id', NULL])->select('categories_imkuh_id')]), 'pgid', 'name'),
            ],
            [
                'attribute' => 'categories_holodbar_id',
                'value' => function ($model) {
                    if (isset($model->categoriesHolodbar->name)){
                        return $model->categoriesHolodbar->name;
                    }else{
                        return false;
                    }

                },
                'filter' => ArrayHelper::map(CategoriesHolodbar::findAll(['pgid' => Groups::find()->where(['is not', 'categories_holodbar_id', NULL])->select('categories_holodbar_id')]), 'pgid', 'name'),
            ],
            [
                'attribute' => 'manufacturer_id_imkuh',
                'value' => function ($model) {
                    if (isset($model->manufacturerIdImkuh->name)){
                        return $model->manufacturerIdImkuh->name;
                    }else{
                        return false;
                    }

                },
                'filter' => ArrayHelper::map(Manufacturer::find()->where(['imkuh' => 1])->all(), 'id', 'name'),
            ],
            [
                'attribute' => 'manufacturer_id_holodbar',
                'value' => function ($model) {
                    if (isset($model->manufacturerIdHolodbar->name)){
                        return $model->manufacturerIdHolodbar->name;
                    }else{
                        return false;
                    }

                },
                'filter' => ArrayHelper::map(Manufacturer::find()->where(['holodbar' => 1])->all(), 'id', 'name'),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
