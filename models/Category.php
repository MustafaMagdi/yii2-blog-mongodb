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
            // validate slug
            [
                [
                    'slug',
                ],
                'validateSlug',
                'skipOnEmpty' => false
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
     * make sure that the slug is unique over your DB and generate it if empty
     *
     * @param $attribute string
     * @param $params array
     */
    public function validateSlug($attribute, $params)
    {
        $module = Yii::$app->getModule('blog');
        $used_languages = $module->used_languages;

        $query = $this->find();

        $slugs = [];

        foreach ($used_languages as $language) {
            if(empty($this->slug[$language]) && !empty($this->title[$language])) {
                $slugs[$language] = Helper::slugify($this->title[$language]);
            } else {
                $slugs[$language] = $this->slug[$language];
            }

            // check if empty
            if(empty($slugs[$language])) {
                break;
            }

            $query->andFilterWhere(['=', "slug.{$language}", $slugs[$language]]);

            // in case of update
            if(!$this->isNewRecord) {
                $query->andFilterWhere(['<>', "_id", $this->_id]);
            }

            if($query->exists()) {
                $this->addError($attribute, Yii::t('app', Yii::t('app', 'Slug "{value}" is token in language "{lang}"', [
                    'value' => $slugs[$language],
                    'lang' => $language
                ])));
                break;
            }
        }
        $this->slug = $slugs;
    }

    /**
     * @return array of documents labels
     */
    public function attributeLabels()
    {
        return [];
    }

    /**
     * setup hints
     *
     * @return array of hints
     */
    function attributeHints()
    {
        $hints['slug'] = Yii::t('app', 'Leave empty to autogenerate');
        return $hints;
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
     * @return string _id
     */
    public function getId()
    {
        return (string) $this->_id;
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
