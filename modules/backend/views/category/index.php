<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel devmustafa\blog\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Category'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            '_id',
//            'id',
            [
                'attribute' => 'title',
                'value' => function($model) {
                    return $model->getTitle();
                },
            ],
            [
                'attribute' => 'slug',
                'value' => function($model) {
                    return $model->getSlug();
                },
            ],
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

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}'
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
