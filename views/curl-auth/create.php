<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CurlAuth */

$this->title = 'Create Curl Auth';
$this->params['breadcrumbs'][] = ['label' => 'Curl Auths', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="curl-auth-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
