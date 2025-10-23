<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;

?>

<div class="nguon-nuoc-ccc-form">

    <?php $form = ActiveForm::begin(); ?>

    <h4>Xóa thông tin nguồn nước PCCC : <?= $model->ten_nguon ?></h4>

    <?php ActiveForm::end(); ?>

</div>

