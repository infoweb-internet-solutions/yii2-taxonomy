<?php

?>
<div class="tab-content default-language-tab">
    <?= $form->field($model, "[{$model->language}]name")->textInput([
        'maxlength' => 255,
        'name' => "Lang[{$model->language}][name]"
    ]); ?>

</div>