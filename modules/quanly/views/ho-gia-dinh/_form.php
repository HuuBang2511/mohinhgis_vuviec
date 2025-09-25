<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\modules\services\UserInterfaceServices;
//use wbraganca\dynamicform\DynamicFormWidget;
use app\widgets\maskedinput\MaskedInput;
use app\widgets\maskedinput\MaskedInputAsset;

use app\widgets\dynamicform\DynamicFormWidget;
use app\widgets\dynamicform\DynamicFormAsset;
use yii\web\JsExpression;

MaskedInputAsset::register($this);
DynamicFormAsset::register($this);

$js = '
jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("Thành viên: " + (index + 1))
    });
});

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("Thành viên: " + (index + 1))
    });
});
';

$this->registerJs($js);
$requestedAction = Yii::$app->requestedAction;
$controller = $requestedAction->controller;
$label = $controller->label;

$this->title = Yii::t('app', $label[$requestedAction->id] . ' ' . $controller->title);
$this->params['breadcrumbs'][] = ['label' => $label['index'] . ' ' . $controller->title, 'url' => Yii::$app->urlManager->createUrl(['quanly/ho-gia-dinh/index'])];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="hogiadinh-form">

    <div class="block block-themed">
        <div class="block-header">
            <h3 class="block-title"><?= $this->title ?></h3>
        </div>
        <div class="block-content">
            <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
            <h2 class="content-heading text-uppercase">Thông tin hộ gia đình</h2>
            <div class="row">
                <div class="col-lg-12">
                    <?= $form->field($hogiadinh, 'nocgia_id')->widget(Select2::className(), [
                                'initValueText' =>  ($diachiNocgia != null ? $diachiNocgia['text'] : '') ,
                                'options' => ['placeholder' => 'Tìm kiếm nóc gia ...', 'id' => 'nocgia_id'],
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
                <div class="col-lg-3">
                    <?= $form->field($hogiadinh, 'ma_hsct')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            

            <h2 class="content-heading text-uppercase">Thông tin thành viên hộ</h2>
            <div class="row">
                <div class="col-lg-12">
                    <?php DynamicFormWidget::begin([
                        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                        'widgetBody' => '.container-items', // required: css class selector
                        'widgetItem' => '.item', // required: css class
                        'limit' => 10, // the maximum times, an element can be cloned (default 999)
                        'min' => 0, // 0 or 1 (default 1)
                        'insertButton' => '.add-item', // css class
                        'deleteButton' => '.remove-item', // css class
                        'model' => $thanhviens[0],
                        'formId' => 'dynamic-form',
                        'formFields' => [
                            'ho_ten',
                            'ngaysinh',
                            'gioitinh_id',
                            'so_dien_thoai',
                            'loaicutru_id',
                            'quanhechuho_id',
                            'cccd',
                            'cccd_ngaycap',
                            'cccd_noicap',
                        ],
                    ]); ?>
                    <div class="block block-themed block-bordered">
                        <div class="block-header">
                            <h3 class="block-title">
                                <i class="fa fa-user"></i> Thành viên hộ gia đình
                            </h3>
                            <div class="block-options">
                                <button type="button" class="pull-right add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> Thêm thành viên</button>
                            </div>
                        </div>
                        <div class="block-content container-items"><!-- widgetContainer -->
                            <?php foreach ($thanhviens as $index => $thanhvien) : ?>
                                <div class="item block block-themed w-100 d-block h-100 block-bordered"><!-- widgetBody -->
                                    <div class="block-header">
                                        <h3 class="block-title">
                                            <span class="panel-title-address">Thành viên <?= ($index + 1) ?></span>
                                        </h3>
                                        <div class="block-options">
                                            <button type="button" class="pull-right remove-item btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
                                        </div>
                                    </div>
                                    <div class="block-content">
                                        <?php
                                        // necessary for update action.
                                        if (!$thanhvien->isNewRecord) {
                                            echo Html::activeHiddenInput($thanhvien, "[{$index}]id");
                                        }
                                        ?>

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <?= $form->field($thanhvien, "[{$index}]ho_ten")->textInput(['maxlength' => true]) ?>
                                            </div>
                                            <div class="col-sm-3">
                                                <?= $form->field($thanhvien, "[{$index}]so_dien_thoai")->textInput(['maxlength' => true]) ?>
                                            </div>
                                            <div class="col-sm-3">
                                                <?= $form->field($thanhvien, "[{$index}]ngaysinh")->widget(MaskedInput::class, [
                                                    'clientOptions' => ['alias' =>  'date']
                                                ]) ?>
                                            </div>
                                            <div class="col-sm-3">
                                                    <?= $form->field($thanhvien, "[{$index}]gioitinh_id")->widget(Select2::class, [
                                                        'data' => ArrayHelper::map($categories['gioitinh'], 'id', 'ten'),
                                                        'options' => ['prompt' => 'Chọn giới tính'],
                                                        'pluginOptions' => [
                                                            'allowClear' => true
                                                        ],
                                                    ]) ?>
                                            </div>
                                            
                                        </div><!-- end:row -->

                                        <div class="row">
                                            <div class="col-sm-3">
                                                    <?= $form->field($thanhvien, "[{$index}]loaicutru_id")->widget(Select2::class, [
                                                        'data' => ArrayHelper::map($categories['loaicutru'], 'id', 'ten'),
                                                        'options' => ['prompt' => 'Chọn loại cư trú'],
                                                        'pluginOptions' => [
                                                            'allowClear' => true
                                                        ],
                                                    ]) ?>
                                            </div>
                                            <div class="col-sm-3">
                                                <?= $form->field($thanhvien, "[{$index}]cccd")->textInput(['maxlength' => true]) ?>
                                            </div>
                                            <div class="col-sm-3">
                                                <?= $form->field($thanhvien, "[{$index}]cccd_ngaycap")->widget(MaskedInput::class, [
                                                    'clientOptions' => ['alias' =>  'date']
                                                ]) ?>
                                            </div>
                                            <div class="col-sm-3">
                                                <?= $form->field($thanhvien, "[{$index}]cccd_noicap")->textInput(['maxlength' => true]) ?>
                                            </div>
                                            <div class="col-sm-3">
                                                    <?= $form->field($thanhvien, "[{$index}]quanhechuho_id")->widget(Select2::class, [
                                                        'data' => ArrayHelper::map($categories['quanhechuho'], 'id', 'ten'),
                                                        'options' => ['prompt' => 'Chọn quan hệ chủ hộ'],
                                                        'pluginOptions' => [
                                                            'allowClear' => true
                                                        ],
                                                    ]) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php DynamicFormWidget::end(); ?>

                </div>
            </div>
            <?= UserInterfaceServices::renderFormFooterButtons()?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>