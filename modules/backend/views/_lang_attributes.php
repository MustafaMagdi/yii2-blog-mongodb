<?php

foreach ($lang_attributes as $attribute => $type) {
    if ($type == 'text') {
        echo $form->field($model, "{$attribute}[$language]")->textarea();
    } elseif ($type == 'editor') {
        echo $form->field($model, "{$attribute}[$language]")->widget(
            \zxbodya\yii2\tinymce\TinyMce::className(),
            [
                'fileManager' => [
                    'class' => \zxbodya\yii2\elfinder\TinyMceElFinder::className(),
                    'connectorRoute' => 'post/connector',
                ],
            ]
        );
    } elseif($type == 'image') {
        if (!empty($model->{$attribute}[$language])) {
            ?>
            <img src="<?= $model->{$attribute}[$language] ?>" height="200" />
            <?php
        }
        echo $form->field($model, "{$attribute}[$language]")->fileInput();
    } else {
        echo $form->field($model, "{$attribute}[$language]");
    }
}
