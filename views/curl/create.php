<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Curl */

$this->title = 'Создать Curl';
$this->params['breadcrumbs'][] = ['label' => 'Curls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="curl-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
