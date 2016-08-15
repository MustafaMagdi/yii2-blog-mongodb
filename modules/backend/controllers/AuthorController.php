<?php

namespace devmustafa\blog\modules\backend\controllers;

use Yii;
use devmustafa\blog\models\Author;
use devmustafa\blog\models\AuthorSearch;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuthorController implements the CRUD actions for Author model.
 */
class AuthorController extends Controller
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
     * Lists all Author models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Author model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Author();

        if ($model->load(Yii::$app->request->post())) {
            $model->img = UploadedFile::getInstance($model, 'img');
            if ($file_name = $model->upload()) {
                // file is uploaded successfully
                $model->img = $file_name;
            }
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
     * Updates an existing Author model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $_id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $img = $model->img;

        if ($model->load(Yii::$app->request->post())) {
            $model->img = UploadedFile::getInstance($model, 'img');
            if ($file_name = $model->upload()) {
                // file is uploaded successfully
                $model->img = $file_name;
            } else {
                $model->img = $img;
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
     * Deletes an existing Author model.
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
     * Finds the Author model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return Author the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Author::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
