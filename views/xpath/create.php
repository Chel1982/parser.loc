<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Xpath */

$this->title = 'Создать Xpath';
$this->params['breadcrumbs'][] = ['label' => 'Xpaths', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="xpath-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
