<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use app\widgets\maps\LeafletMap;
use app\widgets\maps\layers\Marker;
use app\widgets\maps\types\LatLng;
use app\widgets\maps\types\Point;
use app\widgets\maps\types\Icon;
use app\widgets\maps\controls\Layers;
use app\widgets\maps\controls\Scale;
use app\modules\services\MapService;
use app\widgets\maps\LeafletMapAsset;
use app\widgets\crud\CrudAsset;

LeafletMapAsset::register($this);
CrudAsset::register($this);

use yii\bootstrap5\Modal;
use app\modules\services\UtilityService;

use app\widgets\maps\layers\TileLayer;



$this->title = "Chi tiết vụ việc: " . $model->ma_vu_viec;
$this->params['breadcrumbs'][] = ['label' => 'Danh sách vụ việc', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<style>
.card-custom {
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}
.card-custom .card-header {
    background: #f8f9fa;
    font-weight: 600;
    font-size: 15px;
    padding: 10px 15px;
    border-bottom: 1px solid #e0e0e0;
}
.card-custom .card-body {
    padding: 20px;
    background: #FFF;
}
.detail-view th {
    width: 30%;
    background: #fafafa;
}
.detail-view td {
    background: #fff;
}
</style>

<div class="vu-viec-view container-fluid">
    <!-- Tiêu đề + action -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><?= Html::encode($this->title) ?></h3>
        <div>
            <?= Html::a('<i class="fa fa-edit"></i> Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
            <?= Html::a('<i class="fa fa-arrow-left"></i> Quay lại', ['index'], ['class' => 'btn btn-light']) ?>
        </div>
    </div>

    <!-- Thông tin vụ việc -->
    <?php if(!Yii::$app->user->identity->is_nguoidan && Yii::$app->user->identity->nguoidan_id == null): ?>
    <div class="card-custom">
        <div class="card-header">Thông tin vụ việc</div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table table-bordered detail-view'],
                'attributes' => [
                    'ma_vu_viec',
                    'ngay_tiep_nhan',
                    'han_xu_ly',
                    'dia_chi_su_viec',
                    [
                        'attribute' => 'nguoi_dan_id',
                        'value' => function($model) {
                            return $model->nguoiDan ? $model->nguoiDan->ho_ten : null;
                        }
                    ],
                    [
                        'attribute' => 'linh_vuc_id',
                        'value' => function($model) {
                            return $model->linhVuc ? $model->linhVuc->ten_linh_vuc : null;
                        }
                    ],
                    [
                        'attribute' => 'don_vi_tiep_nhan_id',
                        'value' => function($model) {
                            return $model->donViTiepNhan ? $model->donViTiepNhan->ten_don_vi : null;
                        }
                    ],
                    [
                        'attribute' => 'can_bo_tiep_nhan_id',
                        'value' => function($model) {
                            return $model->canBoTiepNhan ? $model->canBoTiepNhan->ho_ten : null;
                        }
                    ],
                    [
                        'attribute' => 'trang_thai_hien_tai_id',
                        'value' => function($model) {
                            return $model->trangThaiHienTai ? $model->trangThaiHienTai->ten_trang_thai : null;
                        }
                    ],
                    'so_nguoi_anh_huong',
                    [
                        'attribute' => 'is_lap_lai',
                        'value' => function($model) {
                            return $model->is_lap_lai ? 'Có' : 'Không';
                        }
                    ],
                    'diem_rui_ro',
                    'muc_do_canh_bao',
                    [
                        'attribute' => 'ma_dvhc_phuongxa',
                        'value' => function($model) {
                            return $model->phuongXa ? $model->phuongXa->tenXa : null;
                        }
                    ],
                    [
                        'attribute' => 'objectid_khupho',
                        'value' => function($model) {
                            return $model->objectid_khupho ? $model->khupho->TenKhuPho : null;
                        }
                    ],
                    'diem_cam_tinh',
                ],
            ]) ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Nội dung vụ việc -->
    <div class="card-custom">
        <div class="card-header">Nội dung vụ việc</div>
        <div class="card-body">
            <h6 class="fw-bold">Địa chỉ sự việc</h6>
            <p><?= nl2br(Html::encode($model->dia_chi_su_viec)) ?></p>
            <h6 class="fw-bold">Tóm tắt</h6>
            <p><?= nl2br(Html::encode($model->tom_tat_noi_dung)) ?></p>
            <h6 class="fw-bold">Mô tả chi tiết</h6>
            <p><?= nl2br(Html::encode($model->mo_ta_chi_tiet)) ?></p>
        </div>
    </div>

    <!-- Lịch sử xử lý -->
    <div class="card-custom">
        <div class="card-header">Lịch sử xử lý</div>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $lichsus,
                    'pagination' => false,
                ]),
                'summary' => false,
                'tableOptions' => ['class' => 'table table-bordered table-striped'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'trang_thai_id',
                        'label' => 'Trạng thái xử lý',
                        'value' => function($lichsu) {
                            return $lichsu->trangThai ? $lichsu->trangThai->ten_trang_thai : null;
                        },
                    ],
                    [
                        'attribute' => 'can_bo_thuc_hien_id',
                        'label' => 'Cán bộ xử lý',
                        'value' => function($lichsu) {
                            return $lichsu->canBoThucHien ? $lichsu->canBoThucHien->ho_ten : null;
                        },
                    ],
                    [
                        'attribute' => 'ghi_chu_xu_ly',
                        'label' => 'Ghi chú xử lý',
                    ],
                    [
                        'attribute' => 'ngay_thuc_hien',
                        'label' => 'Ngày thực hiện',
                    ],
                ],
            ]) ?>
        </div>
    </div>

    <!-- File đính kèm -->
    <div class="card-custom">
        <div class="card-header">File đính kèm</div>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $filedinhkems,
                    'pagination' => false,
                ]),
                'summary' => false,
                'tableOptions' => ['class' => 'table table-bordered table-hover'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'ten_file_goc',
                        'format' => 'raw',
                        'value' => fn($item) =>
                            Html::a($item->ten_file_goc, Yii::$app->homeUrl . $item->duong_dan_file, ['target' => '_blank']),
                    ],
                ],
            ]) ?>
        </div>
    </div>

    <div class="card-custom">
        <div class="card-header">File đính kèm người dân gửi</div>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $filedinhkemNguoidans,
                    'pagination' => false,
                ]),
                'summary' => false,
                'tableOptions' => ['class' => 'table table-bordered table-hover'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'ten_file_goc',
                        'format' => 'raw',
                        'value' => fn($item) =>
                            Html::a($item->ten_file_goc, Yii::$app->homeUrl . $item->duong_dan_file, ['target' => '_blank']),
                    ],
                ],
            ]) ?>
        </div>
    </div>

    <!-- Bản đồ -->
    <div class="card-custom">
        <div class="card-header">Vị trí sự việc</div>
        <div class="card-body">
            <?php
            $center = new LatLng([
                'lat' => ($model->lat != null) ? $model->lat : 10.770178,
                'lng' => ($model->long != null) ? $model->long : 106.668657
            ]);
            $marker = new Marker([
                'latLng' => $center,
                'icon' => new Icon([
                    'iconUrl' => 'https://auth.hcmgis.vn/uploads/icon/icons8-map-marker-96.png',
                    'iconSize' => new Point(['x' => 40, 'y' => 40]),
                    'iconAnchor' => new Point(['x' => 20, 'y' => 40]),
                ]),
            ]);

            $hcmgis_layer = new TileLayer([
                'urlTemplate' => 'https://thuduc-maps.hcmgis.vn/thuducserver/gwc/service/wmts?layer=thuduc:thuduc_maps&style=&tilematrixset=EPSG:900913&Service=WMTS&Request=GetTile&Version=1.0.0&Format=image/png&TileMatrix=EPSG:900913:{z}&TileCol={x}&TileRow={y}',
                'layerName' => 'HCMGIS',
                    'clientOptions' => [
                    'layers' => 'thuduc:thuduc_maps'
                ],
            ]);

            $osm_layer = new TileLayer([
                'urlTemplate' => 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                'layerName' => 'OSM',
                'clientOptions' => [
                    'attribution' => '© OpenStreetMap contributors',
                    'maxZoom' => 22,
                ],
            ]);

            $leaflet = new LeafletMap([
                'center' => $center, // set the center
            ]);

            $layers_control = new Layers();
            $layers_control->setBaseLayers(MapService::createBaseMaps());
            $leaflet->addControl($layers_control);
            $leaflet->addLayer($marker);
            $leaflet->addControl(new Scale());
            $leaflet->addLayer($hcmgis_layer);

            $leaflet->addLayer($hcmgis_layer)->addLayer($marker);

            echo $leaflet->widget(['styleOptions' => ['height' => '450px']]);
            ?>
        </div>
    </div>
</div>
