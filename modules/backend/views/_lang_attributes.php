<?php

foreach ($lang_attributes as $attribute => $type) {
    if ($type == 'text') {
        echo $form->field($model, "{$attribute}[$language]")->textarea();
    } elseif ($type == 'wysiwyg') {
        echo $form->field($model, "{$attribute}[$language]")->widget(vova07\imperavi\Widget::className(), [
            'settings' => [
                'minHeight' => 200,
                'toolbarFixed' => false,
                'pastePlainText' => true,
                'imageUpload' => \yii\helpers\Url::to(['image-upload']),
                'buttons' => [
                    'html',
                    'formatting',
                    'bold',
                    'italic',
                    'underline',
                    'deleted',
                    'unorderedlist',
                    'orderedlist',
                    'outdent',
                    'indent',
                    'image',
                    'file',
                    'link',
                    'alignment',
                    'horizontalrule',
                ],
                'plugins' => [
                    'video',
                    'fullscreen',
                    'table',
                ],
            ],
        ]);
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
