<?php

namespace devmustafa\blog;

/**
 * frontend module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'devmustafa\blog\controllers';

    /**
     * @inheritdoc
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
