<?php

/**
 * DB model for posts
 *
 * @author Mustafa Magdi <developer.mustafa@gmail.com>
 * @link https://github.com/devmustafa/yii2-blog-mongodb
 */

namespace devmustafa\blog\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "blog_posts".
 *
 * @property int $_id
 * @property int $id
 * @property int $author_id
 * @property int $user_country_id
 * @property int $category_id
 * @property int $views
 * @property bool $is_published
 * @property bool $is_notification_sent
 * @property string $publish_date
 * @property string $created_at
 * @property string $updated_at
 * @property string $title
 * @property string $slug
 * @property string $intro
 * @property string $body
 * @property string $tags
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $image_origin
 * @property string $image_thumb
 */
class Post extends \yii\mongodb\ActiveRecord
{
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'blog_posts';
    }

    /**
     * @return array of attributes/documents
     */
    public function attributes()
    {
        return [
            '_id',
            'id',
            'author_id', // ref
            'category_id', // ref
            'views',
            'is_published',
            'is_notification_sent',
            'publish_date',
            'created_at',
            'updated_at',
            'title',
            'slug',
            'intro',
            'body',
            'tags',
            'meta_keywords',
            'meta_description',
            'image_origin',
            'image_thumb'
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
                    'views',
                ],
                'integer'
            ],
            [
                [
                    'is_published',
                    'is_notification_sent',
                ],
                'boolean'
            ],
            [
                [
                    'author_id', // ref
                    'category_id', // ref
                    'publish_date',
                    'created_at',
                    'updated_at',
                ],
                'string'
            ],
            [
                [
                    'image_origin',
                    'image_thumb'
                ],
                'image',
                'skipOnEmpty' => true
            ],
            // check unique slug
            [
                [
                    'slug',
                ],
                'each',
                'rule' => ['unique']
            ],
            // check valid slug
            [
                [
                    'slug',
                ],
                'each',
                'rule' => ['match', 'pattern' => '/^[a-z0-9]+(?:-[a-z0-9]+)*$/']
            ],
            [
                [
                    'title',
                    'slug',
                ],
                'each',
                'rule' => ['trim']
            ],
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
                    'intro',
                    'body',
                    'tags',
                    'meta_keywords',
                    'meta_description',
                ],
                'safe'
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
            if (empty($this->slug[$language]) && !empty($this->title[$language])) {
                $slugs[$language] = Helper::slugify($this->title[$language]);
            } else {
                $slugs[$language] = $this->slug[$language];
            }

            // check if empty
            if (empty($slugs[$language])) {
                break;
            }

            $query->andFilterWhere(['=', "slug.{$language}", $slugs[$language]]);

            // in case of update
            if (!$this->isNewRecord) {
                $query->andFilterWhere(['<>', "_id", $this->_id]);
            }

            if ($query->exists()) {
                $this->addError($attribute,
                    Yii::t('app', Yii::t('app', 'Slug "{value}" is token in language "{lang}"', [
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
        return [
            'author_id' => Yii::t('app', 'Author'),
            'category_id' => Yii::t('app', 'Category')
        ];
    }

    /**
     * @return array of documents that are being used in the multilanguage form
     */
    public function langAttributes()
    {
        return [
            'title' => 'string',
            'slug' => 'string',
            'intro' => 'text',
            'body' => 'wysiwyg',
            'tags' => 'string',
            'meta_keywords' => 'text',
            'meta_description' => 'text',
        ];
    }

    /**
     * setup hints
     *
     * @return array of hints
     */
    function attributeHints()
    {
        $hints['slug'] = Yii::t('app', 'Leave empty to autogenerate');
        $hints['tags'] = Yii::t('app', 'Tags are comma separated');
        return $hints;
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
     * @return string of post name based on the current application language
     */
    public function getTitle()
    {
        return isset($this->title[$this->getWebsiteLang()]) ? $this->title[$this->getWebsiteLang()] : null;
    }

    /**
     * @return string of post slug based on the current application language
     */
    public function getSlug()
    {
        return isset($this->slug[$this->getWebsiteLang()]) ? $this->slug[$this->getWebsiteLang()] : null;
    }

    /**
     * @return string of post intro based on the current application language
     */
    public function getIntro()
    {
        return isset($this->intro[$this->getWebsiteLang()]) ? $this->intro[$this->getWebsiteLang()] : null;
    }

    /**
     * @return string of post body based on the current application language
     */
    public function getBody()
    {
        return isset($this->body[$this->getWebsiteLang()]) ? $this->body[$this->getWebsiteLang()] : null;
    }

    /**
     * @return string of post tags based on the current application language
     */
    public function getTags()
    {
        return isset($this->tags[$this->getWebsiteLang()]) ? $this->tags[$this->getWebsiteLang()] : null;
    }

    /**
     * @return array of post tags based on the current application language
     */
    public function getTagsArray()
    {
        $tags = $this->getTags();
        if ($tags) {
            return explode(',', $tags);
        }
        return null;
    }

    /**
     * @return string of post meta keywords based on the current application language
     */
    public function getMetaKeywords()
    {
        return isset($this->meta_keywords[$this->getWebsiteLang()]) ? $this->meta_keywords[$this->getWebsiteLang()] : null;
    }

    /**
     * @return string of post meta description based on the current application language
     */
    public function getMetaDescription()
    {
        return isset($this->meta_description[$this->getWebsiteLang()]) ? $this->meta_description[$this->getWebsiteLang()] : null;
    }

    /**
     * @return object of category
     */
    public function getCategry()
    {
        return $this->hasOne(Category::className(), ['_id' => 'category_id']);
    }

    /**
     * @return object of category
     */
    public function getCategoryObj()
    {
        return Category::findOne($this->category_id);
    }

    /**
     * @return array of active categories
     */
    public function getActiveCategories()
    {
        $categories = Category::find()
            ->where(['is_active' => '1'])
            ->all();
        $arr = [];
        foreach ($categories as $category) {
            $arr[(string)$category->_id] = $category->getTitle();
        }
        return $arr;
    }

    /**
     * @return object of author
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::className(), ['_id' => 'author_id']);
    }

    /**
     * @return object of author
     */
    public function getAuthorObj()
    {
        return Author::findOne($this->author_id);
    }

    /**
     * @return array of active authors
     */
    public function getActiveAuthors()
    {
        $authors = Author::find()
            ->where(['is_active' => '1'])
            ->all();
        $arr = [];
        foreach ($authors as $author) {
            $arr[(string)$author->_id] = $author->getName();
        }
        return $arr;

    }

    /**
     * upload post image
     *
     * @return string of photo name or
     * @return bool if not done
     */
    public function upload()
    {
        if ($this->validate() && $this->image_origin !== null) {
            // get an awesome file name
            $img_name = str_replace(' ', '-',
                    $this->image_origin->baseName) . '-' . time() . '-' . uniqid() . '.' . $this->image_origin->extension;

            $this->image_origin->saveAs($this->getUploadDirectory() . '/' . $img_name);
            return $img_name;
        } else {
            return false;
        }
    }

    /**
     * if path is not found, just create and return
     *
     * @return string path
     */
    public function getUploadDirectory()
    {
        $directory = \Yii::getAlias('@frontend') . '/web/uploads/posts';
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        return $directory;
    }

    /**
     * @return post image link
     */
    public function getUploadUrl()
    {
        // get module variables
        $module = Yii::$app->getModule('blog');

        // if `front_url` is defined
        if (isset($module->front_url)) {
            $front_url = $module->front_url;
        } else {
            // so you are on frontend app
            $front_url = Url::base();
        }
        return $front_url . '/uploads/posts';
    }

    /**
     * @return post original image link
     */
    public function getImgOriginUrl()
    {
        // get module variables
        $module = Yii::$app->getModule('blog');

        // if `front_url` is defined
        if (isset($module->front_url)) {
            $front_url = $module->front_url;
        } else {
            // so you are on frontend app
            $front_url = Url::base();
        }
        return $front_url . '/uploads/posts/' . $this->image_origin;
    }

    /**
     * search and returen the list of queries
     *
     * @param $offset int of pagination
     * @param $limit int of posts
     * @param $q string query character
     *
     * @return $query object
     */
    public function getPostsList($offset, $limit, $q = '')
    {
        $query = $this->find();
        $query->andFilterWhere(['like', 'is_published', 1])
            ->offset($offset)
            ->limit($limit);

        // used language
        $default_language = $this->getWebsiteLang();

        // ignore posts with no titles or bodies
        $query->andFilterWhere([
                "title.{$default_language}" => [
                    '$ne' => ''
                ],
                "body.{$default_language}" => [
                    '$ne' => ''
                ]
            ]
        );

        // if search happened
        if (!empty($q)) {
            $query->andFilterWhere(['like', "title.{$default_language}", $q])
                ->orFilterWhere(['like', "body.{$default_language}", $q])
                ->orFilterWhere(['like', "tags.{$default_language}", $q]);
        }

        // return query object
        return $query;
    }

    /**
     * get posts by category
     *
     * @param $offset int of pagination
     * @param $limit int of posts
     * @param $category_slug string query character
     *
     * @return $query object
     */
    public function getPostsByCategory($offset, $limit, $category_slug)
    {
        $query = $this->find();
        $query->andFilterWhere(['like', 'is_published', 1])
            ->offset($offset)
            ->limit($limit);

        // used language
        $default_language = $this->getWebsiteLang();

        // ignore posts with no titles or bodies
        $query->andFilterWhere([
                "title.{$default_language}" => [
                    '$ne' => ''
                ],
                "body.{$default_language}" => [
                    '$ne' => ''
                ]
            ]
        );

        // check category
        $category = Category::findOne([
            "slug.{$default_language}" => $category_slug
        ]);
        if($category != null) {
            $query->andFilterWhere(["category_id" => $category->getId()]);
        }

        // return query object
        return $query;
    }

    /**
     * return a single post by slyg
     *
     * @param $slug string
     *
     * @return $query object
     */
    public function getSinglePost($slug)
    {
        // default language
        $default_language = $this->getWebsiteLang();

        $query = $this->find()
            ->andFilterWhere(['=', "slug.{$default_language}", $slug]);

        // ignore if no title or body
        $query->andFilterWhere([
                "title.{$default_language}" => [
                    '$ne' => ''
                ],
                "body.{$default_language}" => [
                    '$ne' => ''
                ]
            ]
        );

        // return query object
        return $query;
    }
}
