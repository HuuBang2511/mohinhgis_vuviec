<?php

use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\crud\CrudAsset;
use yii\grid\GridView;
use app\modules\services\UtilityService;
use yii\widgets\DetailView;
use app\widgets\maps\LeafletMapAsset;
use yii\data\ArrayDataProvider;
use app\widgets\maps\plugins\leafletlocate\LeafletLocateAsset;

LeafletMapAsset::register($this);
LeafletLocateAsset::register($this);
CrudAsset::register($this);




use app\widgets\maps\layers\TileLayer;



$this->title = "Chi tiết vụ việc: " . $model->ma_vu_viec;
$this->params['breadcrumbs'][] = ['label' => 'Danh sách vụ việc', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<!-- CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
<!-- JS -->
<script src="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.js"></script>

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

<style>
#map {
    width: 100%;
    height: 50vh;
    border: 1px solid #0665d0
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
            <div id="map"></div>
        </div>
    </div>
</div>

<script type="module">
   
    var map = L.map('map').setView([<?= ($model->lat != null) ? $model->lat : '10.763496612971204' ?>,
        <?= ($model->long != null) ? $model->long : '106.6465187072754' ?>
    ], 20);


    var layerGMapSatellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });

    var layerGmapStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });


    var baseLayers = {
        "GGMap": layerGmapStreets,
        "Vệ tinh": layerGMapSatellite,
    };

    
    L.control.layers(baseLayers).addTo(map);
    map.addLayer(layerGmapStreets, true);

    var icon = L.icon({
        iconUrl: 'https://auth.hcmgis.vn/uploads/icon/icons8-map-marker-96.png',
        iconSize: [40, 40],
        iconAnchor: [20, 40],
        popupAnchor: [0, -48],
    });

    <?php if ($model->lat != null && $model->long != null) : ?>
    var marker = L.marker([<?= $model->lat ?>, <?= $model->long ?>], {
        'icon': icon,
    }).addTo(map);
    <?php endif; ?>

    L.control.locate({
        position: 'topleft',
        flyTo: true,
        keepCurrentZoomLevel: true,
        drawCircle: false,
        showPopup: false,
        strings: {
            title: "Định vị vị trí của bạn"
        },
        icon: 'fa fa-location-arrow', // nếu bạn dùng font-awesome
        locateOptions: {
            enableHighAccuracy: true,
            maxZoom: 18,
            watch: false
        },
        clickBehavior: {
            inView: 'stop', 
            outOfView: 'setView', 
            inViewNotFollowing: 'setView'
        }
    }).addTo(map);

    setTimeout(() => {
        const btn = document.querySelector('.leaflet-control-locate a');
        if (btn) {
            btn.addEventListener('touchstart', function (e) {
                e.preventDefault();
                btn.click(); // kích hoạt click bằng touch
            });
        }
    }, 1000);
    
</script>
