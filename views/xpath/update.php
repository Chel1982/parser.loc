<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Xpath */

$this->title = 'Обновить XPath: ' . $model->regular;
$this->params['breadcrumbs'][] = ['label' => 'XPaths', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->regular, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="xpath-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
