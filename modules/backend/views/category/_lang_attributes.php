<?php
foreach ($lang_attributes as $attribute => $type) {
    if($type == 'text') {
        echo $form->field($model, "{$attribute}[$language]")->textarea();
        continue;
    }
    echo $form->field($model, "{$attribute}[$language]");
}
