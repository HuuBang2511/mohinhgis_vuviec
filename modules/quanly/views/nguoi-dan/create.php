<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\NguoiDan */

?>
<div class="nguoi-dan-create">
    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories
    ]) ?>
</div>
