<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\VuViec */

?>
<div class="vu-viec-create">
    <?= $this->render('_form', [
        'model' => $model,
        'lichsus' => $lichsus,
        'categories' => $categories,
        'nguoidan' => null,
        'filedinhkem' => $filedinhkem,
        'congdan' => $congdan,
        'filedinhkemNguoidan' => $filedinhkemNguoidan,
    ]) ?>
</div>
