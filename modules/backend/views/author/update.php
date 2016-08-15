<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Author */

// set used language
$default_language = $module_vars['default_language'];

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Author',
]) . $model->name[$default_language];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Authors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="author-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'module_vars' => $module_vars,
    ]) ?>

</div>
