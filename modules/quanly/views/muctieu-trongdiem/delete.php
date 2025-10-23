<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;

?>

<div class="muctieu-trongdiem-form">

    <?php $form = ActiveForm::begin(); ?>

    <h4>Xóa mục tiêu trọng điểm : <?= $model->ten ?></h4>

    <?php ActiveForm::end(); ?>

</div>

