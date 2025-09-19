<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\VuViec */
?>
<div class="vu-viec-update">

    <?= $this->render('_form', [
        'model' => $model,
        'lichsus' => $lichsus,
        'categories' => $categories,
        'nguoidan' => $nguoidan,
        'filedinhkem' => $filedinhkem,
        'congdan' => $congdan,
        'filedinhkemNguoidan' => $filedinhkemNguoidan,
    ]) ?>

</div>
