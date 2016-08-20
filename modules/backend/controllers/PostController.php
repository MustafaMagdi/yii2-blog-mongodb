<?php

namespace devmustafa\blog\modules\backend\controllers;

use Yii;
use devmustafa\blog\models\Post;
use devmustafa\blog\models\PostSearch;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
        return [
            'image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadAction',
                'url' => (new Post())->getUploadUrl(), // Directory URL address, where files are stored.
                'path' => (new Post())->getUploadDirectory(), // Or absolute path to directory where files are stored.
            ],
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
            $model->image_origin = UploadedFile::getInstance($model, 'image_origin');
            if ($file_name = $model->upload()) {
                // file is uploaded successfully
                $model->image_origin = $file_name;
            }
            // todo: generate thumbnails
            if($model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            // get module variables
            $module = Yii::$app->getModule('blog');
            $module_vars = get_object_vars($module);

            return $this->render('create', [
                'model' => $model,
                'module_vars' => $module_vars,
            ]);
        }
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
        $image_origin = $model->image_origin;

        if ($model->load(Yii::$app->request->post())) {
            $model->image_origin = UploadedFile::getInstance($model, 'image_origin');
            if ($file_name = $model->upload()) {
                // file is uploaded successfully
                $model->image_origin = $file_name;
                // todo: generate thumbnails
            } else {
                $model->image_origin = $image_origin;
            }
            if($model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            // get module variables
            $module = Yii::$app->getModule('blog');
            $module_vars = get_object_vars($module);

            return $this->render('update', [
                'model' => $model,
                'module_vars' => $module_vars,
            ]);
        }
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
