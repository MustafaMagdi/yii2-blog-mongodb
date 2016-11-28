<?php

/**
 * frontend - posts
 *
 * @author Mustafa Magdi <developer.mustafa@gmail.com>
 * @link https://github.com/devmustafa/yii2-blog-mongodb
 */

namespace devmustafa\blog\modules\frontend\controllers;

use Yii;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\data\Pagination;
use devmustafa\blog\models\Post;

/**
 * Site controller
 */
class PostController extends Controller
{
    /**
     * @var object
     */
    public $module;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->module = Yii::$app->getModule('blog');
    }

    /**
     * list of posts
     * @return object view of posts list
     */
    public function actionIndex()
    {
        // get page
        $limit = $this->module->listing_size;
        $page_number = Yii::$app->request->get('page', 1);
        $offset = $page_number - 1; // get offset

        $q = Yii::$app->request->get('q', '');

        // return query object
        $query = (new Post)->getPostsList($offset, $limit, '', $q);

        $posts = $query->all();

        $pages = new Pagination([
            'totalCount' => $query->count(),
            'defaultPageSize' => $limit,
            'pageSize' => $limit,
            'route' => '/posts',
        ]);

        return $this->render('index', [
            'posts' => $posts,
            'pages' => $pages,
        ]);
    }

    /**
     * single post based on the slug passed
     * @return object view of single post
     */
    public function actionSingle()
    {
        // get slug
        $slug = Yii::$app->request->get('slug');

        // return query object
        $query = (new Post)->getSinglePost($slug);

        $post = $query->one();

        if($post === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('single', [
            'post' => $post,
        ]);
    }

    /**
     * single post based on the slug passed
     * @return object view of single post
     */
    public function actionCategory()
    {
        // get page
        $limit = $this->module->listing_size;
        $page_number = Yii::$app->request->get('page', 1);
        $offset = $page_number - 1; // get offset

        $slug = Yii::$app->request->get('slug', '');

        // return query object
        $query = (new Post)->getPostsByCategory($offset, $limit, $slug);

        $posts = $query->all();

        $pages = new Pagination([
            'totalCount' => $query->count(),
            'defaultPageSize' => $limit,
            'pageSize' => $limit,
            'route' => '/category',
        ]);

        return $this->render('index', [
            'posts' => $posts,
            'pages' => $pages,
        ]);
    }

}
