Yii2 Blog MongoDB
=================

It's a multi-language blog extension that uses Mongodb that gives you an awesome performance.

Check [this tutorial](http://www.tutorialspoint.com/mongodb/) that I like, in order to know more about Mongodb.


Installation
============

* You have to have Mongodb up and running, so download it [from here](https://www.mongodb.com/download-center).
* Also you have to have [Mongodb PHP](http://php.net/manual/en/mongodb.installation.php) driver installed.

And you run Mongodb through the following command:


    mongod --fork --logpath /var/log/mongodb/mongodb.log


* And you setup the extension through the composer:


    composer require devmustafa/yii2-blog-mongodb


Or add the following line in your composer.json:


    "devmustafa/yii2-blog-mongodb": "*"


Configuration
=============

1. In your common config file add the following db component:


        ...
        'components' => [
            ...
            'mongodb' => [
                'class' => '\yii\mongodb\Connection',
                'dsn' => 'mongodb://127.0.0.1/DB_MONGO_NAME', // local
                // 'dsn' => 'mongodb://DB_MONGO_USERNAME:DB_MONGO_PASSWORD@DB_MONGO_HOST/DB_MONGO_NAME, // remote
            ],
            ...
        ],
        ...


2. In your frontend config file add the following module:


        ...
        'modules' => [
            ...
            'blog' => [
                'class' => devmustafa\blog\modules\frontend\Module::className(),
                'used_languages' => ['en', 'fr'], // list of languages used
                'default_language' => 'en', // default language
                'listing_size' => 10, // default size of listing page
            ]
            ...
        ]
        ...


3. In your backend config file add the following module:


        ...
        'modules' => [
            ...
            'blog' => [
                'class' => devmustafa\blog\modules\backend\Module::className(),
                'front_url' => 'http://yourdomain.local', // blog url
                'used_languages' => ['en', 'fr'], // list of languages used
                'default_language' => 'en', // default language
                'listing_size' => 10, // default size of listing page
            ]
            ...
        ]
        ...


##### Note:

`default_language` must be one of your `used_languages` array, and if you would like to use a dynamic language in your app, so leave it empty:


    'default_language' => '', // empty (don't remove)


In this case the extension reading the value of `Yii::$app->langauge` and it should be one of your defined `used_languages` array.


Usage
=====

1. Go to your backend url to add some posts/authors/categories:
    * /blog/post
    * /blog/author
    * /blog/category

2. Then you check it out on your frontend:
    * /posts


