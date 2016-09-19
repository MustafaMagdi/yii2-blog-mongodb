<?php

/**
 * frontend module definition class
 *
 * @author Mustafa Magdi <developer.mustafa@gmail.com>
 * @link https://github.com/devmustafa/yii2-blog-mongodb
 */

namespace devmustafa\blog;

class Module extends \yii\base\Module
{

    public $controllerNamespace = 'devmustafa\blog\controllers';

    /**
     * define the two submodules for front/backend
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        $this->modules = [
            'frontend' => [
                'class' => 'devmustafa\blog\modules\frontend\Module',
            ],
            'backend' => [
                'class' => 'devmustafa\blog\modules\backend\Module',
            ]
        ];
    }
}
