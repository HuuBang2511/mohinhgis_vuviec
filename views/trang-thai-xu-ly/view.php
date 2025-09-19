<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\TrangThaiXuLy */
?>
<div class="trang-thai-xu-ly-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'ten_trang_thai',
            'mo_ta:ntext',
        ],
    ]) ?>

</div>
