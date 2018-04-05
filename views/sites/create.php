<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Sites */

$this->title = 'Создать Сайт';
$this->params['breadcrumbs'][] = ['label' => 'Сайты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sites-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
