<?php
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
     * @inheritdoc
     */
    public function actionIndex()
    {
        // get page
        $limit = $this->module->listing_size;
        $page_number = Yii::$app->request->get('page', 1);
        $offset = $page_number - 1; // get offset

        $q = Yii::$app->request->get('q', '');

        // return query object
        $query = (new Post)->getPostsList($offset, $limit, $q);

        $posts = $query->all();

        $pages = new Pagination([
            'totalCount' => $query->count(),
            'defaultPageSize' => $limit,
            'pageSize' => $limit,
            'route' => '/post',
        ]);

        return $this->render('index', [
            'posts' => $posts,
            'pages' => $pages,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function actionSingle()
    {
        // get slug
        $slug = Yii::$app->request->get('slug');

        // return query object
        $query = (new Post)->getSinglePost($slug);

        $post = $query->one();
        if($post != null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('single', [
            'post' => $post,
        ]);
    }

}
