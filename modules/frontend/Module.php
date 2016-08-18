<?php

namespace devmustafa\blog\modules\frontend;

use Yii;

/**
 * frontend module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
	public $listing_size;

    /**
     * @inheritdoc
     */
	public $used_languages;

    /**
     * @inheritdoc
     */
	public $default_language;

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'devmustafa\blog\modules\frontend\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here

        // check if user does not support language to use
        if(empty($this->default_language)) {
            $this->default_language = Yii::$app->language;
        }
    }
}
