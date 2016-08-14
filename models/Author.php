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
        return Yii::$app->language;
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
            $img_name = str_replace(' ', '-',
                    $this->img->baseName) . '-' . time() . '-' . uniqid() . '.' . $this->img->extension;
            $this->img->saveAs(FRONTEND_UPLOAD_PATH . '/authors/' . $img_name);
            return $img_name;
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getImgUrl()
    {
        return FRONTEND_BASE_URL . '/uploads/authors/' . $this->img;
    }
}
