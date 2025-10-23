<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;

?>

<div class="cosokinhdoanh-codk-form">

    <?php $form = ActiveForm::begin(); ?>

     <h4>Xóa thông tin cơ sở kinh doanh có điều kiện : <?= $model->ten_co_so ?></h4>

    <?php ActiveForm::end(); ?>

</div>

