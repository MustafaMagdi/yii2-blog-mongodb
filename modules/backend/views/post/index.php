<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use devmustafa\blog\models\Post;

/* @var $this yii\web\View */
/* @var $searchModel devmustafa\blog\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Posts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Post'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // '_id',
            // 'id',
            [
                'attribute' => 'title',
                'value' => function ($model) {
                    return $model->getTitle();
                }
            ],
            [
                'attribute' => 'slug',
                'value' => function ($model) {
                    return $model->getSlug();
                }
            ],
            [
                'attribute' => 'author_id',
                'value' => function ($model) {
                    return $model->getAuthorObj() != null ? $model->getAuthorObj()->getName() : null;
                },
                'filter' => (new Post())->getActiveAuthors()
            ],
            [
                'attribute' => 'category_id',
                'value' => function ($model) {
                    return $model->getCategoryObj() != null ? $model->getCategoryObj()->getTitle() : null;
                },
                'filter' => (new Post())->getActiveCategories()
            ],
            [
                'attribute' => 'is_published',
                'value' => function($model) {
                    return $model->is_published ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
                },
                'filter' => [
                    '1' => Yii::t('app', 'Yes'),
                    '0' => Yii::t('app', 'No')
                ]
            ],
            // 'views',
            // 'publish_date',
            // 'created_at',
            // 'updated_at',
            // 'intro',
            // 'body',
            // 'tags',
            // 'meta_keywords',
            // 'meta_description',
            // 'image',
            // 'image_thumb',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}'
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?></div>
