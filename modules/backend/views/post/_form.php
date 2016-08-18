<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model devmustafa\blog\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $this->render('_tabs', [
        'model' => $model, 'form' => $form, 'module_vars' => $module_vars
    ]) ?>

    <?= $form->field($model, 'author_id')->dropDownList($model->getActiveAuthors(),
        ['prompt' => Yii::t('app', 'Choose Author')]); ?>

    <?= $form->field($model, 'category_id')->dropDownList($model->getActiveCategories(),
        ['prompt' => Yii::t('app', 'Choose Category')]); ?>

    <?= $form->field($model, 'is_published')->inline()->radioList([
        '1' => 'Yes',
        '0' => 'No',
    ]); ?>

    <?= $form->field($model, 'publish_date', [
        'inputOptions' => [
            'type' => 'date',
        ]
    ]) ?>

    <?php
    if (!empty($model->image_origin)) {
        ?>
        <img src="<?= $model->getImgOriginUrl() ?>" height="200" />
        <?php
    }
    echo $form->field($model, 'image_origin')->fileInput();
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
