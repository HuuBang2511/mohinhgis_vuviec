<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\danhmuc\DmGioitinh */
?>
<div class="dm-gioitinh-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            
            'ten',
            
        ],
    ]) ?>

</div>
