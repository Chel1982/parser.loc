<div class="row">
    <div class="col-xs-12">
        <div class="box">


            <?= \yii\grid\GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'name',
                    'url',
                    'down_url',
                    'status',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'buttons'=>[
                            'view'=>function ($url, $model, $key) {
                                return \yii\helpers\Html::a("<span class=\"glyphicon glyphicon-eye-open\"></span>", '/',['target' => '_blank']);
                            }
                        ],
                    ],

                ],

            ]) ?>


        </div>
    </div>

</div>
