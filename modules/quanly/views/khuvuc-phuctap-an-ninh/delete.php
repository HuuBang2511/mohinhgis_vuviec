<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;

?>

<div class="khuvuc-phuctap-an-ninh-form">

    <?php $form = ActiveForm::begin(); ?>

    <h4>Xóa khu vực an ninh phức tạp :   <?= $model->ten ?></h4>

    <?php ActiveForm::end(); ?>

</div>

