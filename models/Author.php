<?php

/**
 * DB model for authors
 *
 * @author Mustafa Magdi <developer.mustafa@gmail.com>
 * @link https://github.com/devmustafa/yii2-blog-mongodb
 */

namespace devmustafa\blog\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Url;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for table "blog_authors".
 *
 * @property int $_id
 * @property int $id
 * @property string $name
 * @property string $bio
 * @property string $url
 * @property string $img
 * @property bool $is_active
 * @property bool $created_at
 * @property bool $updated_at
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
     * @return array of attributes/documents
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
            [
                [
                    'url',
                ],
                'url'
            ],
            [
                ['img'],
                'image',
                'skipOnEmpty' => true
            ],
            // rules for deep array values
            [
                [
                    'name',
                    'bio',
                ],
                'each', 'rule' => ['trim']
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
     * @return array of documents that are being used in the multilanguage form
     */
    public function langAttributes()
    {
        return [
            'name' => 'string',
            'bio' => 'text',
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
     * @return string of author name based on the current application language
     */
    public function getName()
    {
        return isset($this->name[$this->getWebsiteLang()]) ? $this->name[$this->getWebsiteLang()] : null;
    }

    /**
     * @return string of author bio based on the current application language
     */
    public function getBio()
    {
        return isset($this->bio[$this->getWebsiteLang()]) ? $this->bio[$this->getWebsiteLang()] : null;
    }

    /**
     * upload author photo
     *
     * @return string of photo name
     */
    public function upload()
    {
        if ($this->validate() && $this->img !== null) {
            // get an awesome file name
            $img_name = str_replace(' ', '-',
                    $this->img->baseName) . '-' . time() . '-' . uniqid() . '.' . $this->img->extension;

            $this->img->saveAs($this->getUploadDirectory() . '/' . $img_name);

            // create image urls
            $image_url = $this->getUploadUrl() . '/' . $img_name;
            return $image_url;
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
        // get module variables
        $module = Yii::$app->getModule('blog');

        $directory = $module->upload_directory . '/authors';
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
        return $upload_url . '/authors';
    }
}
