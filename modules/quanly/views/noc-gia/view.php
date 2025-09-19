<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\NocGia */
?>
<div class="noc-gia-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'so_nha',
            'ten_duong',
            'khupho_id',
            'phuongxa_id',
            'dia_chi',
            'geom',
            'lat',
            'long',
            'status',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
        ],
    ]) ?>

</div>
