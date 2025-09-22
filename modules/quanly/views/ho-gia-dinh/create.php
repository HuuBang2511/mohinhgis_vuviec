<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\HoGiaDinh */

?>
<div class="ho-gia-dinh-create">
    <?= $this->render('_form', [
        //'model' => $model,
        'hogiadinh' => $hogiadinh,
        //'chuho' => $chuho,
        'nocgia' => $nocgia,
        'thanhviens' => $thanhviens,
        'categories' => $categories,
        'diachiNocgia' => $diachiNocgia,
    ]) ?>
</div>
