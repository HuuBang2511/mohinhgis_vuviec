<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;
use yii\web\JsExpression;




/* @var $this View */
/* @var $model app\modules\quanly\models\PgVungnuoi */

$this->title = (isset($const['title'])) ? $const['title'] : 'Thêm mới';
$this->params['breadcrumbs'][] = ['label' => 'Tài khoản người dùng', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

//dd(ArrayHelper::map($categories['nhomlinhvuc'],'code','ten'));

?>

<div class="auth-user-create">
    <div class="row">
        <div class="offset-lg-3 col-lg-6">
            <div class="block block-themed">
                <div class="block-header">
                    <h3 class="block-title text-uppercase">
                        <?= $this->title ?>
                    </h3>
                </div>
                <div class="block-content padding-tb-05">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'email')->input('email') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'phuongxa')->widget(Select2::className(), [
                                    'data' => ArrayHelper::map($categories['phuongxa'],'ma_dvhc','ten_dvhc'),
                                    'options' => ['id' => 'phuongxa-id', 'prompt' => 'Chọn phường xã'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'donvi_id')->widget(Select2::class, [
                                'data' => ArrayHelper::map($categories['donvi'], 'id', 'ten_don_vi'),                          
                                'options' => ['id' => 'donvi-id' ,'prompt' => 'Chọn đơn vị'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'canbo_id')->widget(DepDrop::class, [
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
                        <div class="col-lg-12">
                            <?= $form->field($model, 'captaikhoan')->widget(Select2::class, [
                                'data' => $categories['captaikhoan'],                          
                                'options' => ['prompt' => 'Chọn cấp tài khoản'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'nguoidan_id')->widget(Select2::className(), [
                                'initValueText' => (!$model->isNewRecord)?  ($nguoidan != null ? $nguoidan['text'] : '') : '' ,
                                'options' => ['placeholder' => 'Tìm kiếm người dân ...'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumInputLength' => 3,
                                    'language' => [
                                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                    ],
                                    'ajax' => [
                                        'url' => Url::to(['../quanly/ajax-data/get-nguoidan']),
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
                    <div class="row">
                        <div class="col-lg-8 col-xl-5">
                            <div class="mb-4">
                                <label class="form-label">Quyền truy cập</label>
                                <div class="space-y-2">
                                    <?php foreach ($groups as $role) : ?>
                                        <div class="form-check ">
                                            <input class="form-check-input" type="checkbox" value="<?= $role['id'] ?>" name="roles[]" <?= in_array($role['id'], $userGroups) ? 'checked' : '' ?>>
                                            <label class="form-check-label"> <?= $role['description'] ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= Html::submitButton('Thêm mới', ['class' => 'btn btn-success']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>


                </div>

            </div>
        </div>
    </div>
</div>