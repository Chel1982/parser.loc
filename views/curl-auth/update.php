<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CurlAuth */

$this->title = 'Обновить Curl Auth: ';
$this->params['breadcrumbs'][] = ['label' => 'Curl Auths', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="curl-auth-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
