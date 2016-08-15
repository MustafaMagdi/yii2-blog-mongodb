<?php

namespace devmustafa\blog\models;

use Yii;

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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
//                'image_origin',
//                'image_thumb'
                ],
                'file'
            ],
            [
                [
                    '_id',
                    'id',
                    'title',
                    'slug',
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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'author_id' => Yii::t('app', 'Author'),
            'category_id' => Yii::t('app', 'Category')
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function langAttributes()
    {
        return [
            'title' => 'string',
            'slug' => 'string',
            'intro' => 'text',
            'body' => 'text',
            'tags' => 'string',
            'meta_keywords' => 'text',
            'meta_description' => 'text',
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

    /**
     * {@inheritdoc}
     */
    public function getIntro()
    {
        return isset($this->intro[$this->getWebsiteLang()]) ? $this->intro[$this->getWebsiteLang()] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return isset($this->body[$this->getWebsiteLang()]) ? $this->body[$this->getWebsiteLang()] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getTags()
    {
        return isset($this->tags[$this->getWebsiteLang()]) ? $this->tags[$this->getWebsiteLang()] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getTagsArray()
    {
        $tags = $this->getTags();
        if($tags) {
            return explode(',', $tags);
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetaKeywords()
    {
        return isset($this->meta_keywords[$this->getWebsiteLang()]) ? $this->meta_keywords[$this->getWebsiteLang()] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetaDescription()
    {
        return isset($this->meta_description[$this->getWebsiteLang()]) ? $this->meta_description[$this->getWebsiteLang()] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getCategry()
    {
        return $this->hasOne(Category::className(), ['_id' => 'category_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function getCategoryObj()
    {
        return Category::findOne($this->category_id);
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveCategories()
    {
        // todo : check '->where(['is_active' => 1])'
        $categories = Category::find()->all();
        $arr = [];
        foreach ($categories as $category) {
            $arr[(string)$category->_id] = $category->getTitle();
        }
        return $arr;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::className(), ['_id' => 'author_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorObj()
    {
        return Author::findOne($this->author_id);
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveAuthors()
    {
        // todo : check '->where(['is_active' => 1])'
        $authors = Author::find()->all();
        $arr = [];
        foreach ($authors as $author) {
            $arr[(string)$author->_id] = $author->getName();
        }
        return $arr;

    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getUploadDirectory()
    {
        $directory = \Yii::getAlias('@frontend') . '/web/uploads/posts';
        if(!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        return $directory;
    }

    /**
     * {@inheritdoc}
     */
    public function getImgOriginUrl()
    {
        // get module variables
        $module = Yii::$app->getModule('blog');
        $front_url = $module->front_url;
        return $front_url . '/uploads/posts/' . $this->image_origin;
    }

    /**
     * {@inheritdoc}
     */
    public function getPostsList($offset, $limit, $q = '')
    {
        $query = $this->find();
        $query->andFilterWhere(['like', 'is_published', 1])
            ->offset($offset)
            ->limit($limit);

        // if search happened
        if (!empty($q)) {
            // used language
            $used_language = Yii::$app->language;

            //
            $query->andFilterWhere(['like', "title.{$used_language}", $q])
                ->orFilterWhere(['like', "body.{$used_language}", $q]);
        }

        // return query object
        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function getSinglePost($slug)
    {
        // used language
        $used_language = Yii::$app->language;

        $query = $this->find()
            ->andFilterWhere(['like', "slug.{$used_language}", $slug]);

        // return query object
        return $query;
    }
}
