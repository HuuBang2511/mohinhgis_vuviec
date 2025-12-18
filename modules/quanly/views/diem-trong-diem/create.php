<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\DiemTrongDiem */

?>
<div class="diem-trong-diem-create">
    <?= $this->render('_form', [
        'model' => $model,
        'filedinhkem' => $filedinhkem,
    ]) ?>
</div>
