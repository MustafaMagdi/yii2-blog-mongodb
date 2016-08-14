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
                'blog/posts' => $moduleId . '/default/index',
                'blog/post/<slug>' => $moduleId . '/default/index',
                // backend
                'blog/posts' => $moduleId . '/default/index',
            ], false);
        }
    }
}
