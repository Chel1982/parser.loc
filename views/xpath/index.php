<?php

use app\models\NameRegular;
use app\models\Sites;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\StringHelper;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\XpathSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Xpaths';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="xpath-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать XPath', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin() ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'sites_id',
                'value' => function ($model) {
                    return StringHelper::truncate($model->sites->name, 35);
                },
                'filter' => ArrayHelper::map(Sites::find()->all(), 'id', 'name'),
            ],
            [
                'attribute' => 'regular',
                'value' => function ($model) {
                    return StringHelper::truncate($model->regular, 120);
                },
            ],

            [
                'attribute' => 'name_regular_id',
                'value' => function ($model) {
                    return StringHelper::truncate($model->nameRegular->name, 35);
                },
                'filter' => ArrayHelper::map(NameRegular::find()->all(), 'id', 'name'),
            ],


            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end() ?>

</div>
