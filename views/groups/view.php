<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Groups */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Группы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="groups-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'created_at',
            'url_group:url',
            [
                'attribute' => 'categories_holodbar_id',
                'format' => 'raw',
                'value' => function($data){

                    $catHolod = \app\models\CategoriesImkuh::find()->where(['pgid' => $data->categories_holodbar_id])->asArray()->one();

                    return Html::a($catHolod['name'], 'http://www.holodbar.ru/content/katalog/' . $catHolod['pmgid'] . '/' . $data->categories_holodbar_id . '/',[
                        'title' => 'Смелей, вперед!',
                        'target' => '_blank'
                    ]);

                }
            ],
            [
                'attribute' => 'categories_imkuh_id',
                'format' => 'raw',
                'value' => function($data){

                    $catImkuh = \app\models\CategoriesImkuh::find()->where(['pgid' => $data->categories_imkuh_id])->asArray()->one();

                    return Html::a($catImkuh['name'], 'http://www.imkuh.ru/group/'. $data->categories_imkuh_id .'/',[
                        'title' => 'Смелей, вперед!',
                        'target' => '_blank'
                    ]);

                }
            ],
        ],
    ]) ?>

</div>
