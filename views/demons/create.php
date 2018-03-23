<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Demons */

$this->title = 'Create Demons';
$this->params['breadcrumbs'][] = ['label' => 'Demons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="demons-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
