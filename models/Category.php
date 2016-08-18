<?php

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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function langAttributes()
    {
        return [
            'title' => 'string',
            'slug' => 'string',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getWebsiteLang()
    {
        // get module variables
        $module = Yii::$app->getModule('blog');
        return $module->default_language;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return isset($this->title[$this->getWebsiteLang()]) ? $this->title[$this->getWebsiteLang()] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlug()
    {
        return isset($this->slug[$this->getWebsiteLang()]) ? $this->slug[$this->getWebsiteLang()] : null;
    }
}
