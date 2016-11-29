<?php

/**
 * DB model for categories
 *
 * @author Mustafa Magdi <developer.mustafa@gmail.com>
 * @link https://github.com/devmustafa/yii2-blog-mongodb
 */

namespace devmustafa\blog\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for table "blog_posts".
 *
 * @property int $_id
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property bool $is_active
 * @property int $created_at
 * @property int $updated_at
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
            'created_at',
            'updated_at',
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
            [
                [
                    '_id',
                    'id',
                    'created_at',
                    'updated_at',
                ],
                'safe'
            ]
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
            // generate or validate slug
            if((empty($this->slug[$language]) && !empty($this->title[$language]))) { // generate
                $slugs[$language] = Helper::slugify($this->title[$language]);
            } elseif(!empty($this->slug[$language])) { // validate
                $slugs[$language] = Helper::slugify($this->slug[$language]);
            }

            // skip if empty
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
     * TimestampBehavior for updating creating and updating dates
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
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
