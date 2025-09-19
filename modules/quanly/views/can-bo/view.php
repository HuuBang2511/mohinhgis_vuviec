<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\CanBo */
?>
<div class="can-bo-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'ho_ten',
            'email:email',
            //'mat_khau',
            [
                'label' => 'Đơn vị',
                'value' => $model->donVi->ten_don_vi,
            ],
            'quyen_han',
        ],
    ]) ?>

</div>
