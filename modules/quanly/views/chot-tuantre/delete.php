<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;

?>

<div class="chot-tuantre-form">

    <?php $form = ActiveForm::begin(); ?>

    <h4>Xóa thông tin chốt tuần tra : <?= $model->ten_chot ?></h4>

    <?php ActiveForm::end(); ?>

</div>

