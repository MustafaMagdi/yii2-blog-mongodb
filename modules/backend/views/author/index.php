<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\AuthorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// set used language
$used_language = Yii::$app->language;

$this->title = Yii::t('app', 'Authors');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Author'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            '_id',
//            'id',
            [
                'attribute' => 'name',
                'value' => function($model) {
                    return $model->getName();
                },
            ],
            [
                'attribute' => 'bio',
                'value' => function($model) {
                    return $model->getBio();
                },
            ],
            'url',
            [
                'attribute' => 'is_active',
                'value' => function($model) {
                    return $model->is_active ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
                },
                'filter' => [
                    '1' => Yii::t('app', 'Yes'),
                    '0' => Yii::t('app', 'No')
                ]
            ],
            // 'img',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}'
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
