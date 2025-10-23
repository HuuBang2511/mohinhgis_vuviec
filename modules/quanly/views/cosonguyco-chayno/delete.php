<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;

?>

<div class="cosonguyco-chayno-form">

    <?php $form = ActiveForm::begin(); ?>

    <h4>Xóa thông tin cơ sở nguy cơ cháy nổ : <?= $model->ten_co_so ?></h4>

    <?php ActiveForm::end(); ?>

</div>

