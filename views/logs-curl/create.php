<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LogsCurl */

$this->title = 'Создать Logs Curl';
$this->params['breadcrumbs'][] = ['label' => 'Logs Curls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logs-curl-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
