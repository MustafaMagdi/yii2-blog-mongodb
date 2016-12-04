<?php

/**
 * DB model for posts
 *
 * @author Mustafa Magdi <developer.mustafa@gmail.com>
 * @link https://github.com/devmustafa/yii2-blog-mongodb
 */

namespace devmustafa\blog\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\mongodb\ActiveRecord;
use yii\helpers\Url;
use yii\imagine\Image;
use yii\web\UploadedFile;

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
 * @property string $image
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
            'image',
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
                    'author_id',
                    'category_id',
                ],
                'required'
            ],
            [
                [
                    'views',
                ],
                'integer'
            ],
            [
                [
                    'is_published',
                ],
                'boolean'
            ],
            [
                [
                    'author_id', // ref
                    'category_id', // ref
                    'publish_date',
                ],
                'string'
            ],
            [
                [
                    'image',
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
                    'created_at',
                    'updated_at',
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
     * format the publish date before save
     */
    function beforeSave($insert)
    {
        $this->publish_date = strtotime($this->publish_date);
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
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
            'intro' => 'wysiwyg',
            'body' => 'wysiwyg',
            'tags' => 'string',
            'meta_keywords' => 'text',
            'meta_description' => 'text',
            'meta_description' => 'text',
            'image' => 'image'
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
     * @return string of post image based on the current application language
     */
    public function getImage()
    {
        return isset($this->image[$this->getWebsiteLang()]) ? $this->image[$this->getWebsiteLang()] : null;
    }

    /**
     * @return string of post image based on the current application language
     */
    public function getPublishDate($format = 'Y-m-d')
    {
        return date($this->publish_date, $format);
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
    public function upload($attribute)
    {
        // get module variables
        $module = Yii::$app->getModule('blog');
        $module_vars = get_object_vars($module);
        $languages = $module_vars['used_languages'];

        $data = [];
        foreach ($languages as $language) {
            $image = UploadedFile::getInstance($this, "{$attribute}[$language]");
            if($image != null) {
                // get an awesome file name
                $img_name = str_replace(' ', '-',
                        $image->getBaseName()) . '-' . time() . '-' . uniqid() . '.' . $image->getExtension();

                // create image directories
                $origin_image_path = $this->getUploadDirectory() . '/' . $img_name;
                $thumb_image_path = $this->getUploadDirectory() . '/' . 'thumb-' . $img_name;

                if($image->saveAs($origin_image_path)) {
                    // generate thumbnail
                    Image::thumbnail($origin_image_path, 120, 120)
                        ->save($thumb_image_path, ['quality' => 100]);

                }
                // create image urls
                $origin_image_url = $this->getUploadUrl() . '/' . $img_name;
                $thumb_image_url = $this->getUploadUrl() . '/' . 'thumb-' . $img_name;
                $data['image'][$language] = $origin_image_url;
                $data['image_thumb'][$language] = $thumb_image_url;
            }
        }
        return $data;
    }

    /**
     * if path is not found, just create and return
     *
     * @return string path
     */
    public function getUploadDirectory()
    {
        // get module variables
        $module = Yii::$app->getModule('blog');

        $directory = $module->upload_directory . '/posts';
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

        // if `upload_url` is defined
        if (isset($module->upload_url)) {
            $upload_url = $module->upload_url;
        }
        return $upload_url . '/posts';
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
        $query->andFilterWhere(['is_published' => true])
            ->orderBy('publish_date DESC')
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
