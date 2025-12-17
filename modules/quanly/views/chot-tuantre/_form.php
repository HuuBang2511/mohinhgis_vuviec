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
use kartik\file\FileInput;

LeafletMapAsset::register($this);

/* @var $this yii\web\View */
/* @var $categories app\modules\quanly\models\DonViKinhTe */
/* @var $form yii\widgets\ActiveForm */

$requestedAction = Yii::$app->requestedAction;
$controller = $requestedAction->controller;
$label = $controller->label;
//dd($controller);

$this->title = Yii::t('app', $label[$requestedAction->id].' '.$controller->title);
$this->params['breadcrumbs'][] = ['label' => $label['index'].' '.$controller->title, 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;


?>

<?php 
    if($model->file_dinhkem != null){
        $file = [];
        $model->file_dinhkem = json_decode($model->file_dinhkem, true);

        foreach($model->file_dinhkem as $i => $item){
            $file[] = Yii::$app->homeUrl.$item;
        }
    }
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
            <div class="col-lg-4">
                <?= $form->field($model, 'ten_chot')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'loai_chot')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'gio_truc')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12">
                <?= $form->field($model, 'don_vi_phu_trach')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12">
                <?= $form->field($model, 'ghi_chu')->textArea(['rows' => 3]) ?>
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
     <?= ($model->lat != null) ? $model->lat : 20.473381288809428 ?>,
    <?= ($model->long != null) ? $model->long : 106.31907196809175 ?>
   
], 16);

// Lớp nền

var googleMap = L.tileLayer('http://{s}.google.com/vt/lyrs=r&x={x}&y={y}&z={z}', {
    maxZoom: 24,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
}).addTo(map);

var vetinh = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
    maxZoom: 24,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
});


L.control.layers(
    {"ggMap": googleMap, "Vệ tinh": vetinh },
).addTo(map);


// Tạo marker
var icon = L.icon({
    iconUrl: '<?= Yii::$app->homeUrl ?>images/icons8-map-marker-96.png',
    iconSize: [40, 40],
    iconAnchor: [20, 20],
    popupAnchor: [0, -48],
});

let lastLatLng = null;
let isManualPosition = false;



const marker = new L.marker([<?= ($model->lat != null) ? $model->lat : 20.473381288809428 ?>,
    <?= ($model->long != null) ? $model->long : 106.31907196809175 ?>
], {
    'draggable': 'true',
    'icon': icon,
}).addTo(map);

// Cập nhật input khi kéo marker
marker.on('dragend', function (event) {
    const position = event.target.getLatLng();
    isManualPosition = true; // đánh dấu người dùng tự chỉnh
    $('#geoy-input').val(position.lng);
    $('#geox-input').val(position.lat);
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



</script>

