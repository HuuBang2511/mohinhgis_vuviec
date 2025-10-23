<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\danhmuc\DmLoaicutru */
?>
<div class="dm-loaicutru-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            
            'ten',
            
        ],
    ]) ?>

</div>
