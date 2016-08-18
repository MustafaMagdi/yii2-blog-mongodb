<?php

use yii\bootstrap\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel devmustafa\blog\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Posts');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div id="postlist">
                <?php foreach ($posts as $post) { ?>
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="text-center">
                                <div class="row">
                                    <div class="col-sm-9">
                                        <h3 class="pull-left">
                                            <?= Html::a($post->getTitle(), Url::to(['/post/' . $post->getSlug()])) ?>
                                        </h3>
                                    </div>
                                    <div class="col-sm-3">
                                        <h4 class="pull-right">
                                            <small><em><?= $post->publish_date ?></em></small>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel-body">
                            <?= $post->getIntro() ?>
                        </div>

                        <?php if(!empty($post->getTagsArray())) { ?>
                            <div class="panel-footer">
                                <?php foreach ($post->getTagsArray() as $tag) { ?>
                                    <span class="label label-default"><?= $tag ?></span>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="col-md-3">
            <?= $this->render('_search_form') ?>
        </div>
        <div class="col-md-12">
            <?php
            // display pagination
            echo LinkPager::widget([
                'pagination' => $pages,
            ]);
            ?>
        </div>
    </div>
</div>