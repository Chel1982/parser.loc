<?php

use app\models\Groups;
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
                        return $model->price_value . ' р';
                    }else{
                        return $model->price_value;
                    }
                },
            ],
            'from_value',
            'to_value',
            [
                'attribute' => 'groups_id',
                'value' => function ($model) {
                    return $model->groups->name;
                },
                'filter' => ArrayHelper::map(Groups::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
            ],
            //'percent:boolean',
            //'absolute:boolean',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
