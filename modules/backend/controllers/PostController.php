<?php

/**
 * crud - posts
 *
 * @author Mustafa Magdi <developer.mustafa@gmail.com>
 * @link https://github.com/devmustafa/yii2-blog-mongodb
 */

namespace devmustafa\blog\modules\backend\controllers;

use Yii;
use devmustafa\blog\models\Post;
use devmustafa\blog\models\PostSearch;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use zxbodya\yii2\elfinder\ConnectorAction;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        // get module variables
        $module = Yii::$app->getModule('blog');

        return [
            'connector' => array(
                'class' => ConnectorAction::className(),
                'settings' => array(
                    'root' => $module->upload_directory,
                    'URL' => $module->upload_url,
                    'rootAlias' => 'Home',
                    'mimeDetect' => 'none'
                )
            ),
        ];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Post();

        if ($model->load(Yii::$app->request->post())) {
            if ($image = $model->upload('image')) {
                // file is uploaded successfully
                $model->image = $image['image'];
                $model->image_thumb = $image['image_thumb'];
            }

            if($model->save()) {
                \Yii::$app->getSession()->setFlash('success', 'Record updated successfully :)');
                return $this->redirect(['update', 'id' => $id]);
            }
        }
        // get module variables
        $module = Yii::$app->getModule('blog');
        $module_vars = get_object_vars($module);

        return $this->render('create', [
            'model' => $model,
            'module_vars' => $module_vars,
        ]);

    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $image_origin = $model->image;

        if ($model->load(Yii::$app->request->post())) {
            if ($image = $model->upload('image')) {
                // file is uploaded successfully
                $model->image = $image['image'];
                $model->image_thumb = $image['image_thumb'];
            } else {
                $model->image = $image_origin;
            }
            if($model->save()) {
                \Yii::$app->getSession()->setFlash('success', 'Record updated successfully :)');
                return $this->redirect(['update', 'id' => $id]);
            }
        }
        // get module variables
        $module = Yii::$app->getModule('blog');
        $module_vars = get_object_vars($module);

        return $this->render('update', [
            'model' => $model,
            'module_vars' => $module_vars,
        ]);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $_id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
