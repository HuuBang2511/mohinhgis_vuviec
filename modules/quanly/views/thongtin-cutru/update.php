<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\ThongtinCutru */
?>
<div class="thongtin-cutru-update">

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
    ]) ?>

</div>
