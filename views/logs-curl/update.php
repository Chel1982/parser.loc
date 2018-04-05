<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LogsCurl */

$this->title = 'Обновить Logs Curl: ';
$this->params['breadcrumbs'][] = ['label' => 'Logs Curls', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="logs-curl-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
