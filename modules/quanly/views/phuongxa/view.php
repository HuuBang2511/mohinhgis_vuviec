<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\Phuongxa */
?>
<div class="phuongxa-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            
            'tenXa',
            'maXa',
            
        ],
    ]) ?>

</div>
