<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model devmustafa\blog\models\Author */

$this->title = Yii::t('app', 'Create Author');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Authors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'module_vars' => $module_vars,
    ]) ?>

</div>
