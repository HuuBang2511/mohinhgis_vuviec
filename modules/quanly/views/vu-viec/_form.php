<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\typeahead\Typeahead;
//use wbraganca\dynamicform\DynamicFormWidget;
use app\widgets\maskedinput\MaskedInput;
use app\widgets\maskedinput\MaskedInputAsset;

use app\widgets\dynamicform\DynamicFormWidget;
use app\widgets\dynamicform\DynamicFormAsset;
use kartik\datetime\DateTimePicker;
use yii\web\JsExpression;
use kartik\depdrop\DepDrop;
use kartik\file\FileInput;

MaskedInputAsset::register($this);
DynamicFormAsset::register($this);

use app\widgets\maps\LeafletMapAsset;

LeafletMapAsset::register($this);

$js = '
jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("Lịch sử xử lý lần: " + (index + 1))
    });
});

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("Lịch sử xử lý lần: " + (index + 1))
    });
});
';

$this->registerJs($js);
$requestedAction = Yii::$app->requestedAction;
$controller = $requestedAction->controller;
$label = $controller->label;

$this->title = Yii::t('app', $label[$requestedAction->id] . ' ' . $controller->title);
$this->params['breadcrumbs'][] = ['label' => $label['index'] . ' ' . $controller->title, 'url' => Yii::$app->urlManager->createUrl(['quanly/vu-viec/index'])];
$this->params['breadcrumbs'][] = $this->title;


?>

<?php 
    if($model->url_dinhkem != null){
        $file = [];
        $model->url_dinhkem = json_decode($model->url_dinhkem, true);

        foreach($model->url_dinhkem as $i => $item){
            $file[] = Yii::$app->homeUrl.$item;
        }
    }

    if($model->url_dinhkem_nguoidan != null){
        $fileNguoidan = [];
        $model->url_dinhkem_nguoidan = json_decode($model->url_dinhkem_nguoidan, true);

        foreach($model->url_dinhkem_nguoidan as $i => $item){
            $fileNguoidan[] = Yii::$app->homeUrl.$item;
        }
    }
?>

<!-- CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
<!-- JS -->
<script src="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.js"></script>

<style>
    .control-label::after{
        content: "*";
        margin-left: 3px;
        font-weight: normal;
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        color: tomato;
    }
</style>

<?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="block block-themed">
            <div class="block-header">
                <h3 class="block-title">
                    <?= $this->title ?>
                </h3>
            </div>
            <div class="d-lg-none py-2 px-2">
                <div class="row">
                    <div class="col-lg-12">
                        <button type="button"
                            class="btn w-100 btn-primary d-flex justify-content-between align-items-center"
                            data-toggle="class-toggle" data-target="#tabs-navigation" data-class="d-none">
                                Menu
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div id="tabs-navigation" class="d-none d-lg-block">
                <ul class="nav nav-tabs nav-tabs-block" role="tablist">
                    <li class="nav-item" role="presentation">
                        <?= Html::button('Thông tin chung', [
                                'type' => 'button',
                                'class' => 'nav-link active',
                                'data-bs-toggle' => 'tab',
                                'data-bs-target' => "#thongtinchung-view",
                        ]) ?>
                    </li>
                    <?php if(!Yii::$app->user->identity->is_nguoidan): ?>
                    <li class="nav-item" role="presentation">
                        <?= Html::button('Người dân', [
                                'id' => 'nguoidan-tab',
                                'type' => 'button',
                                'class' => 'nav-link',
                                'data-bs-toggle' => 'tab',
                                'href' => "#nguoidan-view",
                        ]) ?>
                    </li>
                    <li class="nav-item" role="presentation">
                        <?= Html::button('Lịch sử xử lý', [
                                'id' => 'lichsu-tab',
                                'type' => 'button',
                                'class' => 'nav-link',
                                'data-bs-toggle' => 'tab',
                                'href' => "#lichsu-view",
                        ]) ?>
                    </li>
                    <?php endif; ?>
                    <?php if(Yii::$app->user->identity->is_nguoidan): ?>
                    <li class="nav-item" role="presentation">
                        <?= Html::button('File', [
                                'id' => 'filedinhkem-nguoidan-tab',
                                'type' => 'button',
                                'class' => 'nav-link',
                                'data-bs-toggle' => 'tab',
                                'href' => "#filedinhkem-nguoidan-view",
                        ]) ?>
                    </li>  
                    <?php endif; ?>
                    <?php if(!Yii::$app->user->identity->is_nguoidan): ?>    
                    <li class="nav-item" role="presentation">
                        <?= Html::button('File đính kèm', [
                                'id' => 'filedinhkem-tab',
                                'type' => 'button',
                                'class' => 'nav-link',
                                'data-bs-toggle' => 'tab',
                                'href' => "#filedinhkem-view",
                        ]) ?>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item" role="presentation">
                        <?= Html::button('Vị trí vụ việc', [
                                'type' => 'button',
                                'class' => 'nav-link',
                                'data-bs-toggle' => 'tab',
                                'data-bs-target' => "#bando-view",
                                'id' => 'bando-tab'
                        ]) ?>
                    </li>
                </ul>
            </div>
            <div class="block-content tab-content">
                <div class="tab-pane active" id="thongtinchung-view">
                    <?php if(!Yii::$app->user->identity->is_nguoidan): ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'vu_viec_goc_id')->widget(Select2::class, [
                                'data' => ArrayHelper::map($categories['vuviec'], 'id', 'ma_vu_viec'),                          
                                'options' => ['prompt' => 'Chọn vụ việc gốc', 'id' => 'vuviecgoc_id'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]) ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <label class="control-label" for="vuviec-tom_tat_noi_dung">Tóm tắt Nội dung của vụ việc gốc</label>
                            <textarea id="vuviecgoc_tomtat" class="form-control is-valid" rows="3" aria-required="true" aria-invalid="false" disabled></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'ma_vu_viec')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                        </div>
                        <div class="col-lg-3">
                            <?= $form->field($model, 'ma_dvhc_phuongxa')->widget(Select2::class, [
                                'data' => ArrayHelper::map($categories['phuongxa'], 'maXa', 'tenXa'),                          
                                'options' => ['prompt' => 'Chọn phường xã', 'id' => 'phuongxa-id', 'class' =>'required-field',],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]) ?>
                        </div>
                        <div class="col-lg-3">
                            <?= $form->field($model, 'objectid_khupho')->widget(DepDrop::class, [
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
                        <!-- <div class="col-lg-6">
                            <?= $form->field($model, 'ngay_tiep_nhan')->widget(DateTimePicker::class, [
                                    'options' => ['placeholder' => 'Chọn ngày tiếp nhận ...'],
                                    'pluginOptions' => [
                                        'autoclose' => true,
                                        'format' => 'yyyy-mm-dd HH:ii:ss',
                                        'todayHighlight' => true
                                    ]
                                ]); 
                            ?>
                        </div> -->
                    </div>
                    <div class="row">
                        <div class="col-lg-3">
                            <?= $form->field($model, 'han_xu_ly')->widget(MaskedInput::class, [
                                'clientOptions' => ['alias' =>  'date']
                            ]) ?>
                        </div>
                        <div class="col-lg-3">
                            <?= $form->field($model, 'linh_vuc_id')->widget(Select2::class, [
                                'data' => ArrayHelper::map($categories['linhvuc'], 'id', 'ten_linh_vuc'),                          
                                'options' => ['prompt' => 'Chọn lĩnh vực'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]) ?>
                        </div>
                        <div class="col-lg-3">
                            <?= $form->field($model, 'don_vi_tiep_nhan_id')->widget(Select2::class, [
                                'data' => ArrayHelper::map($categories['donvi'], 'id', 'ten_don_vi'),                          
                                'options' => ['id' => 'donvi-id' ,'prompt' => 'Chọn đơn vị tiếp nhận'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]) ?>
                        </div>
                        <div class="col-lg-3">
                            <?= $form->field($model, 'can_bo_tiep_nhan_id')->widget(DepDrop::class, [
                                'options'=>['id'=>'canbo-id'],
                                'type' => DepDrop::TYPE_SELECT2,
                                'select2Options' => ['pluginOptions' => ['allowClear' => true,]],
                                'pluginOptions'=>[
                                    'depends'=>['donvi-id'],
                                    'initialize' => true,
                                    'placeholder'=>'Chọn cán bộ tiếp nhận',
                                    'url'=>Url::to(['../quanly/ajax-data/get-canbo']),
                                    'allowClear' => true
                                    
                                ]
                            ]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <!-- <div class="col-lg-6">
                            <?= $form->field($model, 'trang_thai_hien_tai_id')->widget(Select2::class, [
                                'data' => ArrayHelper::map($categories['trangthaixuly'], 'id', 'ten_trang_thai'),                          
                                'options' => ['prompt' => 'Chọn trạng thái hiện tại'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]) ?>
                        </div> -->
                        <div class="col-lg-6">
                            <?= $form->field($model, 'so_nguoi_anh_huong')->textInput(['type' => 'number']) ?>
                        </div>
                        <!-- <div class="col-lg-3">
                            <?= $form->field($model, 'diem_rui_ro')->textInput(['maxlength' => true]) ?>
                        </div> -->
                        <div class="col-lg-6">
                            <?= $form->field($model, 'diem_cam_tinh')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'dia_chi_su_viec')->textInput(['maxlength' => true, 'id' => 'vuviec-dia_chi_su_viec']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'tom_tat_noi_dung')->textarea(['rows' => 3, 'id' => 'vuviec-tom_tat_noi_dung']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'mo_ta_chi_tiet')->textarea(['rows' => 3, 'id' => 'vuviec-mo_ta_chi_tiet']) ?>
                        </div>
                    </div>
                    
                </div>
                <?php if(!Yii::$app->user->identity->is_nguoidan): ?>
                <div class="tab-pane" id="nguoidan-view">
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'nguoi_dan_id')->widget(Select2::className(), [
                                'initValueText' => (!$model->isNewRecord)?  ($nguoidan != null ? $nguoidan['text'] : '') : '' ,
                                'options' => ['placeholder' => 'Tìm kiếm người dân ...', 'id' => 'nguoidan_id'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumInputLength' => 3,
                                    'language' => [
                                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                    ],
                                    'ajax' => [
                                        'url' => Url::to('../ajax-data/get-nguoidan'),
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                    ],
                                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                    'templateResult' => new JsExpression('function(city) { return city.text; }'),
                                    'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                                ],
                            ]) ?>
                        </div>
                    </div> 
                    <div class="row" id = "form-themnguoidan">
                        <div class="col-lg-4">
                            <?= $form->field($congdan,'ho_ten')->textInput(['maxlength' => true, 'id' => 'nguoidan_hoten']) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($congdan,'so_dien_thoai')->textInput(['maxlength' => true, 'id' => 'nguoidan_sodienthoai']) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($congdan,'email')->textInput(['maxlength' => true, 'id' => 'nguoidan_email']) ?>
                        </div>
                        <div class="col-lg-12">
                            <?= $form->field($congdan,'dia_chi')->textInput(['maxlength' => true, 'id' => 'nguoidan_diachi']) ?>
                        </div>
                    </div>           
                </div>
                <div class="tab-pane" id="lichsu-view">
                    <?php DynamicFormWidget::begin([
                        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                        'widgetBody' => '.container-items', // required: css class selector
                        'widgetItem' => '.item', // required: css class
                        'limit' => 20, // the maximum times, an element can be cloned (default 999)
                        'min' => 0, // 0 or 1 (default 1)
                        'insertButton' => '.add-item', // css class
                        'deleteButton' => '.remove-item', // css class
                        'model' => $lichsus[0],
                        'formId' => 'dynamic-form',
                        'formFields' => [
                            'can_bo_thuc_hien_id',
                            'trang_thai_id',
                            'ghi_chu_xu_ly',
                            //'ngay_thuc_hien',
                        ],
                    ]); ?>
                    <div class="block block-themed block-bordered">
                        <div class="block-header">
                            <h3 class="block-title">
                                <i class="fa fa-user"></i> Lịch sử xử lý
                            </h3>
                            <div class="block-options">
                                <button type="button" class="pull-right add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> Thêm lịch sử</button>
                            </div>
                        </div>
                        <div class="block-content container-items">
                            <?php foreach ($lichsus as $index => $lichsu) : ?>
                                <div class="item block block-themed w-100 d-block h-100 block-bordered">
                                    <div class="block-header">
                                        <h3 class="block-title">
                                            <span class="panel-title-address">Lịch sử xử lý lần <?= ($index + 1) ?></span>
                                        </h3>
                                        <div class="block-options">
                                            <button type="button" class="pull-right remove-item btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
                                        </div>
                                    </div>
                                    <div class="block-content">
                                        <?php
                                            // necessary for update action.
                                            if (!$lichsu->isNewRecord) {
                                                echo Html::activeHiddenInput($lichsu, "[{$index}]id");
                                            }
                                        ?>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <?= $form->field($lichsu, "[{$index}]trang_thai_id")->widget(Select2::class, [
                                                    'data' => ArrayHelper::map($categories['trangthaixuly'], 'id', 'ten_trang_thai'),
                                                    'options' => ['prompt' => 'Chọn trạng thái'],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ],
                                                ]) ?>
                                            </div>
                                            <div class="col-lg-6">
                                                <?= $form->field($lichsu, "[{$index}]can_bo_thuc_hien_id")->widget(Select2::class, [
                                                    'data' => ArrayHelper::map($categories['canbo'], 'id', 'ho_ten'),
                                                    'options' => ['prompt' => 'Chọn cán bộ'],
                                                    'pluginOptions' => [
                                                        'allowClear' => true,
                                                        //'multiple' => true
                                                    ],
                                                ]) ?>
                                            </div>
                                            <!-- <div class="col-lg-6">
                                                <?= $form->field($lichsu, "[{$index}]ngay_thuc_hien")->widget(DateTimePicker::class, [
                                                        'options' => [
                                                            'placeholder' => 'Chọn ngày thực hiện ...',
                                                            'class' => 'form-control datetimepicker'
                                                        ],
                                                        'pluginOptions' => [
                                                            'autoclose' => true,
                                                            'format' => 'yyyy-mm-dd HH:ii:ss',
                                                            'todayHighlight' => true
                                                        ]
                                                    ]); 
                                                ?>
                                            </div> -->
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <?= $form->field($lichsu, "[{$index}]ghi_chu_xu_ly")->textarea(['rows' => 3]) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php DynamicFormWidget::end(); ?>
                </div>
                <?php endif; ?>
                <?php if(!Yii::$app->user->identity->is_nguoidan): ?>
                <div class="tab-pane" id="filedinhkem-view">
                    <div class="row px-3">
                        <?php if($model->isNewRecord): ?>
                            <div class="col-lg-12">
                                <?= $form->field($filedinhkem, 'fileupload')->widget(FileInput::className(), [
                                    'options'=>[
                                        'multiple'=>true
                                    ],
                                    'pluginOptions' => [
                                        'initialPreviewAsData' => true,
                                        'allowedFileExtensions' => ['png', 'jpg', 'jpeg', 'docx', 'pdf', 'xlsx'],
                                        'showPreview' => true,
                                        'showCaption' => true,
                                        'showRemove' => true,
                                        'showUpload' => false,
                                    ]
                                ])->label('File đính kèm');
                            ?>
                        </div>
                        <?php else: ?>
                        <?php if($model->url_dinhkem != null): ?>
                        <div class="col-lg-12">
                            <?= $form->field($filedinhkem, 'fileupload')->widget(FileInput::className(), [
                                    'options'=>[
                                        'multiple'=>true
                                    ],
                                    'pluginOptions' => [
                                        'overwriteInitial' => true,
                                        'initialPreview' => $file,
                                        'initialPreviewAsData' => true,
                                        'initialPreviewFileType' => 'pdf',
                                        'allowedFileExtensions' => ['png', 'jpg', 'jpeg', 'docx', 'pdf', 'xlsx'],
                                        'showPreview' => true,
                                        'showCaption' => true,
                                        'showRemove' => true,
                                        'showUpload' => false,
                                    ]
                                ])->label('File đính kèm');
                            ?>
                        </div>
                        <?php else: ?>
                        <div class="col-lg-12">
                            <?= $form->field($filedinhkem, 'fileupload')->widget(FileInput::className(), [
                                    'options'=>[
                                        'multiple'=>true
                                    ],
                                    'pluginOptions' => [
                                        'initialPreviewAsData' => true,
                                        'allowedFileExtensions' =>['png', 'jpg', 'jpeg', 'docx', 'pdf', 'xlsx'],
                                        'showPreview' => true,
                                        'showCaption' => true,
                                        'showRemove' => true,
                                        'showUpload' => false,
                                    ]
                                ])->label('File đính kèm');
                            ?>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if(Yii::$app->user->identity->is_nguoidan): ?>
                <div class="tab-pane" id="filedinhkem-nguoidan-view">
                    <div class="row px-3">
                        <?php if($model->isNewRecord): ?>
                            <div class="col-lg-12">
                                <?= $form->field($filedinhkemNguoidan, 'fileupload')->widget(FileInput::className(), [
                                    'options'=>[
                                        'multiple'=>true
                                    ],
                                    'pluginOptions' => [
                                        'initialPreviewAsData' => true,
                                        'allowedFileExtensions' => ['png', 'jpg', 'jpeg', 'docx', 'pdf', 'xlsx'],
                                        'showPreview' => true,
                                        'showCaption' => true,
                                        'showRemove' => true,
                                        'showUpload' => false,
                                    ]
                                ])->label('File đính kèm');
                            ?>
                        </div>
                        <?php else: ?>
                        <?php if($model->url_dinhkem_nguoidan != null): ?>
                        <div class="col-lg-12">
                            <?= $form->field($filedinhkemNguoidan, 'fileupload')->widget(FileInput::className(), [
                                    'options'=>[
                                        'multiple'=>true
                                    ],
                                    'pluginOptions' => [
                                        'overwriteInitial' => true,
                                        'initialPreview' => $fileNguoidan,
                                        'initialPreviewAsData' => true,
                                        'initialPreviewFileType' => 'pdf',
                                        'allowedFileExtensions' => ['png', 'jpg', 'jpeg', 'docx', 'pdf', 'xlsx'],
                                        'showPreview' => true,
                                        'showCaption' => true,
                                        'showRemove' => true,
                                        'showUpload' => false,
                                    ]
                                ])->label('File đính kèm');
                            ?>
                        </div>
                        <?php else: ?>
                        <div class="col-lg-12">
                            <?= $form->field($filedinhkemNguoidan, 'fileupload')->widget(FileInput::className(), [
                                    'options'=>[
                                        'multiple'=>true
                                    ],
                                    'pluginOptions' => [
                                        'initialPreviewAsData' => true,
                                        'allowedFileExtensions' =>['png', 'jpg', 'jpeg', 'docx', 'pdf', 'xlsx'],
                                        'showPreview' => true,
                                        'showCaption' => true,
                                        'showRemove' => true,
                                        'showUpload' => false,
                                    ]
                                ])->label('File đính kèm');
                            ?>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                <div class="tab-pane" id="bando-view" >
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="map" style="height: 600px"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'long')->input('text', ['id' => 'geox-input']) ?>
                        </div>
                        <div class="col-lg-6">
                            <?= $form->field($model, 'lat')->input('text', ['id' => 'geoy-input']) ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?= Html::submitButton('Lưu', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <script>
jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    // reset lại tất cả các datetimepicker trong item vừa được thêm
    $(item).find('.datetimepicker').each(function() {
        $(this).datetimepicker({
            autoclose: true,
            format: 'yyyy-mm-dd HH:ii:ss',
            todayHighlight: true
        });
    });
});
</script> -->

<script>
    const vuviecgoc_id = document.querySelector('#vuviecgoc_id');
    const vuviecgoc_tomtat = document.querySelector('#vuviecgoc_tomtat');
    const vuviec_tomtat = document.querySelector('#vuviec-tom_tat_noi_dung');
    const vuviec_diachi = document.querySelector('#vuviec-dia_chi_su_viec');
    const vuviec_mota = document.querySelector('#vuviec-mo_ta_chi_tiet');

    $("#vuviecgoc_id").change(function() {
        var id = ($("#vuviecgoc_id").val());

        if(id ==''){

        }else{
            $.ajax({
                url: "<?= Yii::$app->homeUrl ?>" + 'quanly/ajax-data/get-data-vuviec?id=' +id,
                dataType: 'json',
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log("Status: " + textStatus);
                    console.log("Error: " + errorThrown);
                },
                data: {

                },
                success: function(data) {
                
                    vuviecgoc_tomtat.innerText = data.tom_tat_noi_dung
                    vuviec_tomtat.value = data.tom_tat_noi_dung
                    vuviec_diachi.value = data.dia_chi_su_viec
                    vuviec_mota.value = data.mo_ta_chi_tiet
                }
            });
        }
    })

    <?php if($model->vu_viec_goc_id != null && !$model->isNewRecord): ?>
        $.ajax({
            url: "<?= Yii::$app->homeUrl ?>" + 'quanly/ajax-data/get-data-vuviec?id=' +<?= $model->vu_viec_goc_id ?>,
            dataType: 'json',
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log("Status: " + textStatus);
                console.log("Error: " + errorThrown);
            },
            data: {

            },
            success: function(data) {
                vuviecgoc_tomtat.innerText = data.tom_tat_noi_dung
            }
        });
    <?php endif; ?>
</script>

<script>
var map = L.map('map').setView([
    <?= ($model->lat != null) ? $model->lat : 20.473381288809428 ?>,
    <?= ($model->long != null) ? $model->long : 106.31907196809175 ?>
], 18);

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
    iconUrl: 'https://auth.hcmgis.vn/uploads/icon/icons8-map-marker-96.png',
    iconSize: [40, 40],
    iconAnchor: [20, 40],
    popupAnchor: [0, -40],
});

let lastLatLng = null;
let isManualPosition = false;

const marker = new L.marker([
    <?= ($model->lat != null) ? $model->lat : 20.473381288809428 ?>,
    <?= ($model->long != null) ? $model->long : 106.31907196809175 ?>
], {
    draggable: true,
    icon: icon,
}).addTo(map);

// Cập nhật input khi kéo marker
marker.on('dragend', function (event) {
    const position = event.target.getLatLng();
    isManualPosition = true; 
    $('#geox-input').val(position.lng);
    $('#geoy-input').val(position.lat);
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

$("#vuviec-dia_chi_su_viec").on("change", function() {
    var address = $(this).val();
    if(address.trim() === '') return;

    $.get("https://nominatim.openstreetmap.org/search", {
        q: address,
        format: "json",
        addressdetails: 1,
        limit: 1
    }, function(data) {
        if(data.length > 0) {
            console.log(data);
            var location = data[0];
            $("#geox-input").val(location.lon);
            $("#geoy-input").val(location.lat);

            // Di chuyển marker
            marker.setLatLng([location.lat, location.lon]);
            map.setView([location.lat, location.lon], 18);
            map.invalidateSize();
            map.panTo(position);
        }
    });
});

// --- Sửa sự kiện Bootstrap 5 tab ---
$('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
    if ($(e.target).data('bsTarget') === '#bando-view') {
        setTimeout(function () {
            map.invalidateSize();
        }, 200);
    }
});
</script>

<script>
    const nguoidan_hoten = document.querySelector('#nguoidan_hoten');
    const nguoidan_sodienthoai = document.querySelector('#nguoidan_sodienthoai');
    const nguoidan_diachi = document.querySelector('#nguoidan_diachi');
    const nguoidan_email = document.querySelector('#nguoidan_email');

    const nguoidan_id = document.querySelector('#nguoidan_id');

    
    $("#nguoidan_id").change(function() {
        var id = ($("#nguoidan_id").val());

        if (id == '') {
            nguoidan_hoten.value = '';
            nguoidan_sodienthoai.value = '';
            nguoidan_diachi.value = '';
            nguoidan_email.value = '';

            nguoidan_hoten.disabled = false;
            nguoidan_sodienthoai.disabled = false;
            nguoidan_email.disabled = false;
            nguoidan_diachi.disabled = false;
        } else {
            $.ajax({
                url: "<?= Yii::$app->homeUrl ?>" + 'quanly/ajax-data/get-data-nguoidan?id=' +
                    id,
                dataType: 'json',
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log("Status: " + textStatus);
                    console.log("Error: " + errorThrown);
                },
                data: {

                },
                success: function(data) {
                    nguoidan_hoten.value = data.ho_ten;
                    nguoidan_sodienthoai.value = data.so_dien_thoai;
                    nguoidan_email.value = data.email;
                    nguoidan_diachi.value = data.dia_chi;

                    <?php if($congdan->isNewRecord): ?>
                    // nguoidan_hoten.disabled = true;
                    // nguoidan_sodienthoai.disabled = true;
                    // nguoidan_email.disabled = true;
                    // nguoidan_diachi.disabled = true;
                    <?php endif; ?>
                }
            });
        }
    })

    <?php if(!$model->isNewRecord && $congdan->isNewRecord): ?>
        var id = ($("#nguoidan_id").val());

        if (id == '') {
            nguoidan_hoten.value = '';
            nguoidan_sodienthoai.value = '';
            nguoidan_diachi.value = '';
            nguoidan_email.value = '';

            nguoidan_hoten.disabled = false;
            nguoidan_sodienthoai.disabled = false;
            nguoidan_email.disabled = false;
            nguoidan_diachi.disabled = false;
        } else {
            $.ajax({
                url: "<?= Yii::$app->homeUrl ?>" + 'quanly/ajax-data/get-data-nguoidan?id=' +
                    id,
                dataType: 'json',
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log("Status: " + textStatus);
                    console.log("Error: " + errorThrown);
                },
                data: {

                },
                success: function(data) {
                    nguoidan_hoten.value = data.ho_ten;
                    nguoidan_sodienthoai.value = data.so_dien_thoai;
                    nguoidan_email.value = data.email;
                    nguoidan_diachi.value = data.dia_chi;

                    nguoidan_hoten.disabled = true;
                    nguoidan_sodienthoai.disabled = true;
                    nguoidan_email.disabled = true;
                    nguoidan_diachi.disabled = true;
                }
            });
        }
    <?php endif; ?>

</script>


<?php ActiveForm::end(); ?>