<?php

use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\crud\CrudAsset;
use app\modules\services\UtilityService;
use kartik\detail\DetailView;
use app\widgets\maps\LeafletMapAsset;
use app\widgets\maps\plugins\leafletlocate\LeafletLocateAsset;

LeafletMapAsset::register($this);
LeafletLocateAsset::register($this);
CrudAsset::register($this);

$this->title = "Chi tiết nóc gia";
$this->params['breadcrumbs'][] = ['label' => 'Danh sách nóc gia', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
<!-- JS -->
<script src="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.js"></script>

<style>
#map {
    width: 100%;
    height: 50vh;
    border: 1px solid #0665d0
}
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="block block-themed">
            <div class="block-header">
                <h3 class="block-title">Thông tin nóc gia</h3>
                <div class="block-options">
                    <?= Html::a('Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
                </div>
            </div>
            <div class="d-lg-none py-2 px-2">
                <div class="row">
                    <div class="col-lg-12">
                        <button type="button" class="btn w-100 btn-primary d-flex justify-content-between align-items-center" data-toggle="class-toggle" data-target="#tabs-navigation" data-class="d-none">
                            Menu
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div id="tabs-navigation" class="d-none d-lg-block">
                <ul class="nav nav-tabs nav-tabs-block" role="tablist">
                    <li class="nav-item" role="presentation">
                        <?= Html::button('Thông tin nóc gia', [
                            'type' => 'button',
                            'class' => 'nav-link active',
                            'data-bs-toggle' => 'tab',
                            'href' => "#thongtinnocgia-view",
                        ]) ?>
                    </li>
                    <li class="nav-item" role="presentation">
                        <?= Html::button('Hộ gia đình thuộc nóc gia', [
                            'type' => 'button',
                            'class' => 'nav-link',
                            'data-bs-toggle' => 'tab',
                            'href' => "#thongtinhogiadinh-view",
                        ]) ?>
                    </li>
                
                </ul>
            </div>

            <div class="block-content tab-content">
                <div class="tab-pane active" id="thongtinnocgia-view">
                    <div class="row">
                        <div class="col-lg-4">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width:35%"><?= $model->getAttributeLabel('so_nha')?></th>
                                    <td><?= $model->so_nha?></td>
                                </tr>
                                <tr>
                                    <th style="width:35%"><?= $model->getAttributeLabel('ten_duong')?></th>
                                    <td><?= $model->ten_duong?></td>
                                </tr>
                                <tr>
                                    <th style="width:35%"><?= $model->getAttributeLabel('khupho_id')?></th>
                                    <td><?= ($model->khupho_id != null) ? $model->khupho->TenKhuPho : '' ?></td>
                                </tr>
                                <tr>
                                    <th style="width:35%"><?= $model->getAttributeLabel('phuongxa_id')?></th>
                                    <td><?= ($model->phuongxa_id != null) ? $model->phuongxa->tenXa : '' ?></td>
                                </tr>
                                
                            </table>
                        </div>
                        <div class="col-lg-8">
                            <div id="map"></div>
                        </div>
                    </div>
                </div>


                <div class="tab-pane" id="thongtinhogiadinh-view">
                    <?php if (isset($hogiadinhs)) : ?>
                        <a href="<?= Yii::$app->homeUrl ?>quanly/ho-gia-dinh/create?id=<?= $_GET['id'] ?>"
                                class="btn  btn-success mb-3 float-end">Thêm mới hộ gia đình</a>
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>STT</th>
                                <th>Mã hộ</th>
                                <th>Chủ hộ</th>
                                <th>Thao tác</th>
                            </tr>
                            <?php if ($hogiadinhs != null) : ?>
                                <?php foreach ($hogiadinhs as $i => $hogiadinh) : ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= $hogiadinh->ma_hsct ?></td>
                                        <td><?= ($hogiadinh->chuho != null) ? $hogiadinh->chuho->ho_ten : '' ?></td>
                                        <td class="text-center">
                                            <a class="btn btn-sm btn-primary" href="<?= Yii::$app->urlManager->createUrl(['quanly/ho-gia-dinh/view','id' => $hogiadinh->id]) ?>"><i class="fa fa-eye"></i></a>
                                            <!-- <a class="btn btn-sm btn-danger" href="<?= Yii::$app->homeUrl ?>administration/nocgia/delete-hogiadinh?id=<?= $hogiadinh->id ?>" data-confirm="Xóa thông hộ gia khỏi nóc gia?"><i class="fa fa-trash"></i></a> -->
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </table>
                    <?php endif; ?>
                </div>

            </div>
            <div class="block-content">
                <div class="row px-3 py-3">
                    <div class="col-lg-12 form-group">
                        <a href="javascript:history.back()" class="btn btn-light float-end"><i class="fa fa-arrow-left"></i>
                            Quay lại</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "size" => Modal::SIZE_EXTRA_LARGE,
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>


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
