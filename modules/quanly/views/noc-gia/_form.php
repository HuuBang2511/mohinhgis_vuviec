<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;
use kartik\depdrop\DepDrop;
use app\widgets\maps\LeafletMapAsset;

LeafletMapAsset::register($this);

/* @var $this yii\web\View */
/* @var $categories app\modules\quanly\models\DonViKinhTe */
/* @var $form yii\widgets\ActiveForm */

$requestedAction = Yii::$app->requestedAction;
$controller = $requestedAction->controller;
$label = $controller->label;

$this->title = Yii::t('app', $label[$requestedAction->id] . ' ' . $controller->title);
$this->params['breadcrumbs'][] = ['label' => $label['index'] . ' ' . $controller->title, 'url' => Yii::$app->urlManager->createUrl(['quanly/vu-viec/index'])];
$this->params['breadcrumbs'][] = $this->title;


?>

<!-- CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
<!-- JS -->
<script src="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.js"></script>

<?php $form = ActiveForm::begin([
    'fieldConfig' => [
        'errorOptions' => ['encode' => false],
    ],
]) ?>

<div class="block block-themed">
    <div class="block-header">
        <h3 class="block-title">
            <?= ($model->isNewRecord) ? 'Thêm mới' : 'Cập nhật' ?>
        </h3>
    </div>

    <div class="block-content">

        <div class="row mt-3">
            <div class="col-lg-3">
                <?= $form->field($model, 'so_nha')->input('text') ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'ten_duong')->input('text') ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'phuongxa_id')->widget(Select2::className(), [
                        'data' => ArrayHelper::map($categories['phuongxa'], 'ma_dvhc', 'ten_dvhc'),
                        'options' => ['prompt' => 'Chọn phường xã', 'id' => 'phuongxa-id'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                ]) ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'khupho_id')->widget(DepDrop::class, [
                                'options'=>['id'=>'khupho-id'],
                                'type' => DepDrop::TYPE_SELECT2,
                                'select2Options' => ['pluginOptions' => ['allowClear' => true,]],
                                'pluginOptions'=>[
                                    'depends'=>['phuongxa-id'],
                                    'initialize' => true,
                                    'placeholder'=>'Chọn khu phố',
                                    'url'=>Url::to(['../quanly/ajax-data/get-khupho']),
                                    'allowClear' => true
                                    
                    ]
                ]) ?>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-6">
                <?= $form->field($model, 'lat')->input('text', ['id' => 'geox-input']) ?>
            </div>
            <div class="col-lg-6">
                <?= $form->field($model, 'long')->input('text', ['id' => 'geoy-input']) ?>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12">
                <div id="map" style="height: 600px"></div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12 pb-3">
                <?= Html::submitButton('Lưu', ['class' => 'btn btn-primary', 'id' => 'submitButton']) ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<script>
var map = L.map('map').setView([
    <?= ($model->long != null) ? $model->long : 10.763496612971204 ?>,
    <?= ($model->lat != null) ? $model->lat : 106.6465187072754 ?>
], 18);

// Lớp nền

var hcmgis = L.tileLayer(
    'https://thuduc-maps.hcmgis.vn/thuducserver/gwc/service/wmts?layer=thuduc:thuduc_maps&style=&tilematrixset=EPSG:900913&Service=WMTS&Request=GetTile&Version=1.0.0&Format=image/png&TileMatrix=EPSG:900913:{z}&TileCol={x}&TileRow={y}', {
        maxZoom: 25,
        minZoom: 13,
}).addTo(map);

var googleMap = L.tileLayer('http://{s}.google.com/vt/lyrs=r&x={x}&y={y}&z={z}', {
    maxZoom: 24,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
});

var vetinh = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
    maxZoom: 24,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
});


L.control.layers(
    { "HCMGIS" : hcmgis ,"ggMap": googleMap, "Vệ tinh": vetinh },
).addTo(map);


// Tạo marker
var icon = L.icon({
    iconUrl: 'https://auth.hcmgis.vn/uploads/icon/icons8-map-marker-96.png',
    iconSize: [40, 40],
    iconAnchor: [20, 20],
    popupAnchor: [0, -48],
});

let lastLatLng = null;
let isManualPosition = false;



const marker = new L.marker([<?= ($model->long != null) ? $model->long : 10.763496612971204 ?>,
    <?= ($model->lat != null) ? $model->lat : 106.6465187072754 ?>
], {
    'draggable': 'true',
    'icon': icon,
}).addTo(map);

// Cập nhật input khi kéo marker
marker.on('dragend', function (event) {
    const position = event.target.getLatLng();
    isManualPosition = true; // đánh dấu người dùng tự chỉnh
    $('#geoy-input').val(position.lat);
    $('#geox-input').val(position.lng);
    map.panTo(position);
});

// Control định vị
const locateControl = L.control.locate({
    position: 'topleft',
    flyTo: true,
    keepCurrentZoomLevel: true,
    drawCircle: false,
    showPopup: false,
    strings: {
        title: "Định vị vị trí của bạn"
    },
    icon: 'fa fa-location-arrow',
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

// Hỗ trợ touchstart trên điện thoại
setTimeout(() => {
    const btn = document.querySelector('.leaflet-control-locate a');
    if (btn) {
        const handleLocate = function (e) {
            e.preventDefault();
            isManualPosition = false;
            map.locate({
                setView: true,
                maxZoom: 18,
                enableHighAccuracy: true,
                watch: false
            });
        };
        btn.addEventListener('click', handleLocate);
        btn.addEventListener('touchstart', handleLocate);
    }
}, 1000);

// Xử lý khi định vị thành công
map.on("locationfound", function(e) {
    if (isManualPosition) return; // bỏ qua nếu người dùng tự chỉnh

    const current = L.latLng(e.latitude, e.longitude);
    if (!lastLatLng || current.distanceTo(lastLatLng) > 5) {
        lastLatLng = current;
        $('#geoy-input').val(e.latitude);
        $('#geox-input').val(e.longitude);
        marker.setLatLng(current);
        map.setView(current, 18);
    }

    if (!isManualPosition) {
        const current = L.latLng(e.latitude, e.longitude);
        lastLatLng = current;

        // Cập nhật vào form
        $('#geoy-input').val(e.latitude);
        $('#geox-input').val(e.longitude);

        // Cập nhật vị trí marker
        marker.setLatLng(current);

        // Đưa map về vị trí
        map.setView(current, 18);
    }
});

// const gpsButton = L.control({ position: 'topleft' });

// gpsButton.onAdd = function(map) {
//     const btn = L.DomUtil.create('button', 'leaflet-bar leaflet-control leaflet-control-custom');
//     btn.innerHTML = '📍';
//     btn.title = 'Quay lại vị trí hiện tại';
//     btn.style.backgroundColor = 'white';
//     btn.style.width = '34px';
//     btn.style.height = '34px';
//     btn.style.cursor = 'pointer';
//     btn.style.fontSize = '18px';
//     btn.style.lineHeight = '30px';
//     btn.style.textAlign = 'center';
//     btn.style.border = 'none';
//     btn.style.boxShadow = '0 1px 5px rgba(0,0,0,0.65)';

//     // Ngăn bản đồ bị kéo khi nhấn
//     L.DomEvent.disableClickPropagation(btn);
//     L.DomEvent.on(btn, 'click', function (e) {
//         e.preventDefault();
//         resetToGPS(); // gọi lại hàm định vị
//     });

//     return btn;
// };

// gpsButton.addTo(map);
// // Hàm gọi lại định vị (có thể gọi từ nút ngoài)
// function resetToGPS() {
//     isManualPosition = false;
//     map.locate({ setView: true, maxZoom: 18, enableHighAccuracy: true, watch: false });
// }

</script>

