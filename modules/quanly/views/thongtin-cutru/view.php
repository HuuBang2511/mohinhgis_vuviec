<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use yii\helpers\Url;

$requestedAction = Yii::$app->requestedAction;

$this->title = "Chi tiết thông tin cư trú";
//$this->params['breadcrumbs'][] = ['label' => 'Danh sách nóc gia', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\CongdanBaotroxahoi */
?>

<div class="congdan-doituongchinhsach-view">
    <div class="row">
        <div class="col-lg-12">
            <div class="block block-themed">
                <div class="block-header">
                    <h3 class="block-title"><?= $this->title ?></h3>
                </div>
                <div class="block-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    [
                                        'attribute' => 'nguoidan_id',
                                        'label' => 'Công dân',
                                        'format' => 'raw',
                                        'value' => function($model) {
                                            if (!$model->nguoidan) return null;
                                            $cd = $model->nguoidan;
                                            $gioitinh = isset($cd->gioitinh) ? $cd->gioitinh->ten : '';
                                            return '<b>' . Html::encode($cd->ho_ten) . '</b><br>' .
                                                'Ngày sinh: ' . Html::encode($cd->ngaysinh) . '<br>' .
                                                'Điện thoại: ' . Html::encode($cd->so_dien_thoai) . '<br>' .
                                                'Giới tính: ' . Html::encode($gioitinh) . '<br>' .
                                                'CCCD: ' . Html::encode($cd->cccd);
                                        },
                                    ],
                                    [
                                        'attribute' => 'loaicutru_id',
                                        'label' => 'Loại tổ chức',
                                        'value' => function($model) {
                                            if (!$model->loaicutru_id) return null;
                                            return $model->loaicutru->ten;
                                        },
                                    ],
                                    
                                    [
                                        'attribute' => 'ngaybatdau',
                                    ],
                                    [
                                        'attribute' => 'ngayketthuc',
                                    ],
                                    [
                                        'attribute' => 'diachi_thuongtru',
                                    ],
                                    [
                                        'attribute' => 'diachi_cutru',
                                    ],
                                    [
                                        'attribute' => 'diachi_tamtru',
                                    ],
                                ],
                            ]) ?>
                        </div>
                    </div>
                    <?php if (!Yii::$app->request->isAjax) { ?>
                    <div class="row">
                        <div class="col-lg-12 pb-3">
                            <?= Html::a('<i class="fa fa-pen"></i> Cập nhật', ['update', 'id' => $model->id], ['class' => 'float-start btn btn-warning']) ?>
                            <?= Html::a('<i class="fa fa-chevron-left"></i> Quay lại',"javascript:history.back()",['class'=>"btn btn-light float-end"])?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
