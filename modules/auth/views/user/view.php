<?php

use kartik\dialog\Dialog;
use yii\helpers\Html;
use yii\widgets\DetailView;


/* @var $this yii\web\View */

$this->title = $model->fullname;
$this->params['breadcrumbs'][] = ['label' => 'Tài khoản người dùng', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Thông tin tài khoản';


?>
<?= Dialog::widget() ?>
<div class="card">
    <h5 class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <div class="text-uppercase">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="header-buttons">
            <?= Html::a('Cập nhật', ['cap-nhat-tai-khoan', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
            <?= Html::a('Xóa', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Bạn có chắc muốn xóa mục này?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </h5>
    <div class="card-body">
        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                //'id',
                //'username',
                //'email:email',
                'fullname',
                'cccd',
                'sodienthoai',
                [
                    'label' => 'Phường xã',
                    'value' => function($model){
                        return ($model->phuongxa != null) ? $model->phuongXa->ten_dvhc : '';
                    }
                ]
            ],
        ])
        ?>
    </div>
</div>