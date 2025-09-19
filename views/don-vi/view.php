<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\DonVi */
?>
<div class="don-vi-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'ten_don_vi',
            'parent_id',
            'loai_don_vi',
        ],
    ]) ?>

</div>
