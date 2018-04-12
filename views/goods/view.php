<?php

use app\models\Images;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Goods */

$this->title = $model->name_goods;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить этот товар?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
        'attributes' => [
            'id',
            'name_goods',
            [
                'attribute' => 'uri_goods',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a(
                        $data->uri_goods,
                        $data->uri_goods,
                        [
                            'title' => 'Смелей, вперед!',
                            'target' => '_blank'
                        ]
                    );
                }
            ],
            'created_at',
            'sites.name',
            'groups.name',
            [
                'attribute' => 'descriptions.main',
                'format' => 'raw',
            ],
            [
                'attribute' => 'descriptions.additional',
                'format' => 'raw',
            ],
            'prices.price',
            'manufacturers.name',
            [
                'attribute' => 'productAttributes.content',
                'format' => 'raw',
            ],
        ],
    ]);

    $images = Images::find()->where(['goods_id' => $model->id])->asArray()->all();

    if($images != null){
        echo '<b> Изображение продукции </b> <br>';
    }
    foreach ($images as $image) {
      echo  Html::img('@web/uploads/images/' . $model->id . '/' . $image['name'], ['alt' => $image['name']]);
      echo '  ';
    }

    ?>

</div>
