<?php

/**
 * add some rules while bootstrapping application
 *
 * @author Mustafa Magdi <developer.mustafa@gmail.com>
 * @link https://github.com/devmustafa/yii2-blog-mongodb
 */

namespace devmustafa\blog;

use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    /**
     * add url rules
     * @param $app object
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
