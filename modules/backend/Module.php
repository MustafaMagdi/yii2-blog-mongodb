<?php

/**
 * backend module class
 *
 * @author Mustafa Magdi <developer.mustafa@gmail.com>
 * @link https://github.com/devmustafa/yii2-blog-mongodb
 */

namespace devmustafa\blog\modules\backend;

/**
 * backend module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * listing size number
     */
	public $listing_size;

    /**
     * array of languages used
     */
	public $used_languages;

    /**
     * the frontend base url
     */
	public $front_url;

    /**
     * default language used
     */
	public $default_language;

    /**
     * routing rules
     */
	public $rules;

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
