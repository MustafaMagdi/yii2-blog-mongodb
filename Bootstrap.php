<?php

namespace devmustafa\blog;

use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\web\Application && $blogModule = Yii::$app->getModule('blog')) {
            $moduleId = $blogModule->id;
            $app->getUrlManager()->addRules([
				// frontend
                'posts' => $moduleId . '/post',
                'post/<slug>' => $moduleId . '/post/single',
            ], false);
        }
    }
}
