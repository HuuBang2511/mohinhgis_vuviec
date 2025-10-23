<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;

?>

<div class="camera-an-ninh-form">

    <?php $form = ActiveForm::begin(); ?>

    <h4>Xóa thông tin camera an ninh mã : <?= $model->ma_camera ?></h4>

    <?php ActiveForm::end(); ?>

</div>

