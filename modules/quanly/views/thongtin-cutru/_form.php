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

/* @var $this yii\web\View */
/* @var $categories app\modules\quanly\models\DonViKinhTe */
/* @var $form yii\widgets\ActiveForm */

$requestedAction = Yii::$app->requestedAction;
$controller = $requestedAction->controller;
$label = $controller->label;

$this->title = Yii::t('app', $label[$requestedAction->id] . ' ' . $controller->title);
$this->params['breadcrumbs'][] = ['label' => $label['index'] . ' ' . $controller->title, 'url' => Yii::$app->urlManager->createUrl(['quanly/noc-gia/index'])];
$this->params['breadcrumbs'][] = $this->title;
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
            
            <div class="col-lg-6">
                <?= $form->field($model, 'loaicutru_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map($categories['loaicutru'], 'id', 'ten'),                          
                    'options' => ['prompt' => 'Chọn loại cư trú'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) ?>
            </div>  
            <div class="col-lg-3">
                <?= $form->field($model, 'ngaybatdau')->widget(MaskedInput::className(), [
                    'clientOptions' => [
                        'alias' => 'date'
                    ],
                ]); ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'ngayketthuc')->widget(MaskedInput::className(), [
                    'clientOptions' => [
                        'alias' => 'date'
                    ],
                ]); ?>
            </div> 
           
        </div>

        <div class="row mt-3">
            <div class="col-lg-12">
                <?= $form->field($model, 'diachi_thuongtru')->input('text') ?>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12">
                <?= $form->field($model, 'diachi_cutru')->input('text') ?>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-lg-12">
                <?= $form->field($model, 'diachi_tamtru')->input('text') ?>
            </div>
        </div>

         <div class="row mt-3">
            <div class="col-lg-12">
                <?= $form->field($model, 'ghichu')->input('text') ?>
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

