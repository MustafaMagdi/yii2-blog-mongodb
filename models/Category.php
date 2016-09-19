<?php

/**
 * DB model for categories
 *
 * @author Mustafa Magdi <developer.mustafa@gmail.com>
 * @link https://github.com/devmustafa/yii2-blog-mongodb
 */

namespace devmustafa\blog\models;

use Yii;

/**
 * This is the model class for table "blog_posts".
 *
 * @property int $_id
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property bool $is_active
 */
class Category extends \yii\mongodb\ActiveRecord
{
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'blog_categories';
    }

    /**
     * @return array of attributes/documents
     */
    public function attributes()
    {
        return [
            '_id',
            'id',
            'title',
            'slug',
            'is_active',
        ];
    }

    /**
     * @return array of the collection rules
     */
    public function rules()
    {
        return [
            [
                [
                    'is_active',
                ],
                'boolean'
            ],
            [
                [
                    '_id',
                    'id',
                ],
                'safe'
            ],
            // rules for deep array values
            [
                [
                    'title',
                    'slug',
                ],
                'each', 'rule' => ['trim']
            ],
            // check unique slug
            [
                [
                    'slug',
                ],
                'each', 'rule' => ['unique']
            ],
            // check valid slug
            [
                [
                    'slug',
                ],
                'each', 'rule' => ['match', 'pattern' => '/^[a-z0-9]+(?:-[a-z0-9]+)*$/']
            ],
        ];
    }

    /**
     * @return array of documents labels
     */
    public function attributeLabels()
    {
        return [];
    }

    /**
     * @return array of documents that are being used in the multilanguage form
     */
    public function langAttributes()
    {
        return [
            'title' => 'string',
            'slug' => 'string',
        ];
    }

    /**
     * @return string of current language code used in the application ex. `en`
     */
    public function getWebsiteLang()
    {
        // get module variables
        $module = Yii::$app->getModule('blog');
        return $module->default_language;
    }

    /**
     * @return string of category name based on the current application language
     */
    public function getTitle()
    {
        return isset($this->title[$this->getWebsiteLang()]) ? $this->title[$this->getWebsiteLang()] : null;
    }

    /**
     * @return string of category slug based on the current application language
     */
    public function getSlug()
    {
        return isset($this->slug[$this->getWebsiteLang()]) ? $this->slug[$this->getWebsiteLang()] : null;
    }
}
