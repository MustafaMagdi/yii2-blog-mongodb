<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model devmustafa\blog\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'author_id')->dropDownList($model->getActiveAuthors(),
                ['prompt' => Yii::t('app', 'Choose Author')]); ?>

            <?= $form->field($model, 'category_id')->dropDownList($model->getActiveCategories(),
                ['prompt' => Yii::t('app', 'Choose Category')]); ?>

            <?= $form->field($model, 'publish_date', [
                'inputOptions' => [
                    'type' => 'date',
                    'value' => $model->publish_date ? date('Y-m-d', $model->publish_date) : '',
                ]
            ]) ?>

            <?= $form->field($model, 'is_published')->checkbox(); ?>
        </div>
        <div class="col-md-8">
            <?= $this->render('/_tabs', [
                'model' => $model,
                'form' => $form,
                'module_vars' => $module_vars
            ]) ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
                    ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
