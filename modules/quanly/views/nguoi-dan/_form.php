<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\select2\Select2;
use app\widgets\maskedinput\MaskedInput;
use kartik\depdrop\DepDrop;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $categories app\modules\quanly\models\DonViKinhTe */
/* @var $form yii\widgets\ActiveForm */

$requestedAction = Yii::$app->requestedAction;
$controller = $requestedAction->controller;
$const['label'] = $controller->const['label'];

$this->title = Yii::t('app', $const['label'][$requestedAction->id] . ' ' . $controller->const['title']);
$this->params['breadcrumbs'][] = ['label' => $const['label']['index'] . ' ' . $controller->const['title'], 'url' => $controller->const['url']['index']];
$this->params['breadcrumbs'][] = $model->isNewRecord ? $const['label']['create'] . ' ' . $controller->const['title'] : $const['label']['update'] . ' ' . $controller->const['title'];

?>

<?php 
    if($model->url_dinhkem != null){
        $file = [];
        $model->url_dinhkem = json_decode($model->url_dinhkem, true);

        foreach($model->url_dinhkem as $i => $item){
            $file[] = Yii::$app->homeUrl.$item;
        }
    }
?>


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

        </div>

        <div class="row mt-3">
            <div class="col-lg-3">
                <?= $form->field($model, 'ho_ten')->input('text') ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'gioitinh_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map($categories['gioitinh'], 'id', 'ten'),                          
                    'options' => ['prompt' => 'Chọn giới tính'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'loaicutru_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map($categories['loaicutru'], 'id', 'ten'),                          
                    'options' => ['prompt' => 'Chọn loại cư trú'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'so_dien_thoai')->input('text') ?>
            </div>

        </div>

        <div class="row mt-3">
            <div class="col-lg-3">
                <?= $form->field($model, 'email')->input('text') ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'cccd')->input('text') ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'cccd_ngaycap')->widget(MaskedInput::className(), [
                    'clientOptions' => [
                        'alias' => 'date'
                    ],
                ]); ?>
            </div>

        </div>

        <div class="row mt-3">
            <div class="col-lg-12">
                <?= $form->field($model, 'cccd_noicap')->input('text') ?>
            </div>
        </div>

        <!-- <div class="row mt-3">
            <div class="col-lg-12">
                <?= $form->field($model, 'dia_chi')->input('text') ?>
            </div>
        </div> -->



        <div class="row mt-3">
            <div class="col-lg-3">
                <?= $form->field($model, 'quanhechuho_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map($categories['quanhechuho'], 'id', 'ten'),                          
                    'options' => ['prompt' => 'Chọn quan hệ chủ hộ'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) ?>
            </div>
        </div>

        <div class="row mt-3">
            <?php if($model->isNewRecord): ?>
            <div class="col-lg-12">
                <?= $form->field($filedinhkem, 'fileupload')->widget(FileInput::className(), [
                    'options'=>[
                        'multiple'=>true
                    ],
                    'pluginOptions' => [
                        'initialPreviewAsData' => true,
                        'allowedFileExtensions' => ['png', 'jpg', 'jpeg'],
                        'showPreview' => true,
                        'showCaption' => true,
                        'showRemove' => true,
                        'showUpload' => false,
                        'fileActionSettings' => [
                            'showRemove' => false, 
                            'showUpload' => false, 
                            'showZoom' => false,   
                            'showDrag' => false,   
                        ],
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
                        'allowedFileExtensions' => ['png', 'jpg', 'jpeg'],
                        'showPreview' => true,
                        'showCaption' => true,
                        'showRemove' => false,
                        'showUpload' => false,
                        'fileActionSettings' => [
                            'showRemove' => false, 
                            'showUpload' => false, 
                            'showZoom' => true,   
                            'showDrag' => false,   
                        ],
                        'initialPreviewConfig' => array_map(function($f) {
                            return ['showRemove' => false]; // tắt remove từng file
                        }, (array)$file),
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
                            'allowedFileExtensions' =>['png', 'jpg', 'jpeg'],
                            'showPreview' => true,
                            'showCaption' => true,
                            'showRemove' => true,
                            'showUpload' => false,
                            'fileActionSettings' => [
                                'showRemove' => false, 
                                'showUpload' => false, 
                                'showZoom' => false,   
                                'showDrag' => false,   
                            ],
                        ]
                    ])->label('File đính kèm');
                ?>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12 pb-3">
                <?= Html::submitButton('Lưu', ['class' => 'btn btn-primary', 'id' => 'submitButton']) ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>