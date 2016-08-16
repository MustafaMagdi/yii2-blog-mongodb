<?php

namespace devmustafa\blog\modules\backend;

/**
 * backend module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
	public $layout = 'main';

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
	public $front_url;

    /**
     * @inheritdoc
     */
	public $default_language;

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'devmustafa\blog\modules\backend\controllers';

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
