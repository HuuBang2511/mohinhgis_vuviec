<?php

/**
 * Created by PhpStorm.
 * User: Duc
 * Date: 8/11/2021
 * Time: 10:49 PM
 */
use kartik\form\ActiveForm;
?>

<?php $form = ActiveForm::begin() ?>

<div class="col-lg-12">
    <?= $form->field($model, 'active')->dropDownList(['1' => 'Mở khoá', '0' => 'Khoá'])->label('Khoá tài khoản') ?>
</div>
<?php
ActiveForm::end()?>