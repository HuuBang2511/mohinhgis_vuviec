<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\LinhVuc */
?>
<div class="linh-vuc-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'ten_linh_vuc',
            'trong_so_nghiem_trong',
        ],
    ]) ?>

</div>
