<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model devmustafa\blog\models\Author */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="author-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $this->render('/_tabs', [
        'model' => $model, 'form' => $form, 'module_vars' => $module_vars
    ]) ?>

    <?= $form->field($model, 'url') ?>

    <?php
    if (!empty($model->img)) {
        ?>
        <img src="<?= $model->getImgUrl() ?>" height="200" />
        <?php
    }
    echo $form->field($model, 'img')->fileInput()
    ?>

    <?= $form->field($model, 'is_active')->inline()->radioList([
        '1' => 'Yes',
        '0' => 'No',
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
