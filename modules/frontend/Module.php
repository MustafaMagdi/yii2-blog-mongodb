<?php

namespace devmustafa\blog\modules\frontend;

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
	public $used_language;

    /**
     * @inheritdoc
     */
	public $default_language;

    /**
     * @inheritdoc
     */
	public $front_url;

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
    }
}
