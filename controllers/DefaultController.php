<?php

namespace devmustafa\blog\controllers;

use yii\web\Controller;

class DefaultController extends Controller {

    /**
     * render dashboard stats
     */
    public function actionIndex() {
        return $this->render('index');
    }
}
