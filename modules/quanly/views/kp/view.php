<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\Kp */
?>
<div class="kp-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            
            'TenKhuPho',
            
        ],
    ]) ?>

</div>
