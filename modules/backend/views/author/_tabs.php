<?php

use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $form yii\widgets\ActiveForm */

//
$used_languages = $module_vars['used_languages'];
$lang_attributes = $model->langAttributes();

$items = [];
$i = 1;
foreach ($used_languages as $language) {
    if ($i == 1) {
        $active = true;
    }
    $items[] = [
        'label' => $language,
        'content' => $this->render('_lang_attributes', ['language' => $language, 'form' => $form, 'model' => $model, 'lang_attributes' => $lang_attributes]),
        'active' => $active
    ];
    $active = false;
    ++$i;
}
?>

<?php
echo Tabs::widget([
    'items' =>
        $items,
]);
?>
