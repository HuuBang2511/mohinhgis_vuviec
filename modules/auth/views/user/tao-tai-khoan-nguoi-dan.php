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

//dd(ArrayHelper::map($categories['nhomlinhvuc'],'code','ten'));

?>

<style>
    body {
        background: url('<?= Yii::$app->request->baseUrl ?>/images/background_thanhtra_moi.png') no-repeat center center fixed;
        background-size: cover;
    }
</style>

<div class="auth-user-create">
    <div class="row">
        <div class="offset-lg-3 col-lg-6">
            <div class="block block-themed">
                <div class="block-header">
                    <h3 class="block-title text-uppercase">
                        Tạo tài khoản người dân
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
                            <?= $form->field($model, 'email')->textInput(['type' => 'email', 'required' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'cccd')->textInput(['required' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'phuongxa')->widget(Select2::className(), [
                                    'data' => ArrayHelper::map($categories['phuongxa'],'maXa','tenXa'),
                                    'options' => ['id' => 'phuongxa-id', 'prompt' => 'Chọn phường xã'],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'required' => true,
                                    ],
                                ]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'sodienthoai')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'diachi')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= Html::submitButton('Tạo tài khoản', ['class' => 'btn btn-success']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>

            </div>
        </div>
    </div>
</div>