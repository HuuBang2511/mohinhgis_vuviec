<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\HoGiaDinh */
?>
<div class="ho-gia-dinh-update">

    <?= $this->render('_form', [
        'hogiadinh' => $hogiadinh,
        //'chuho' => $chuho,
        'thanhviens' => $thanhviens,
        'categories' => $categories,
        'diachiNocgia' => $diachiNocgia,
    ]) ?>

</div>
