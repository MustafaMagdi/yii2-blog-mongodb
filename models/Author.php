<?php

namespace devmustafa\blog\models;

use Yii;

/**
 * This is the model class for table "blog_posts".
 *
 * @property int $_id
 * @property int $id
 * @property string $name
 * @property string $bio
 * @property string $url
 * @property string $img
 * @property bool $is_active
 */
class Author extends \yii\mongodb\ActiveRecord
{
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'blog_authors';
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'id',
            'name',
            'bio',
            'url',
            'img',
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
                    'url',
                ],
                'string'
            ],
            [
                [
                    '_id',
                    'id',
                    'name',
                    'bio',
                ],
                'safe'
            ],
            [['img'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
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
            'name' => 'string',
            'bio' => 'text',
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
    public function getName()
    {
        return isset($this->name[$this->getWebsiteLang()]) ? $this->name[$this->getWebsiteLang()] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getBio()
    {
        return isset($this->bio[$this->getWebsiteLang()]) ? $this->bio[$this->getWebsiteLang()] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function upload()
    {
        if ($this->validate() && $this->img !== null) {
            // get an awesome file name
            $img_name = str_replace(' ', '-',
                    $this->img->baseName) . '-' . time() . '-' . uniqid() . '.' . $this->img->extension;

            $this->img->saveAs($this->getUploadDirectory() . '/' . $img_name);
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
        $directory = \Yii::getAlias('@frontend') . '/web/uploads/authors';
        if(!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        return $directory;
    }

    /**
     * {@inheritdoc}
     */
    public function getImgUrl()
    {
        // get module variables
        $module = Yii::$app->getModule('blog');
        $front_url = $module->front_url;
        return $front_url . '/uploads/authors/' . $this->img;
    }
}
