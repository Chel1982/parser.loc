<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Config */

$this->title = 'Обновить конфиг';
$this->params['breadcrumbs'][] = ['label' => 'Настройка конфига', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="config-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>