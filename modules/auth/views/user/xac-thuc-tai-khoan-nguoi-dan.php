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
                        Xác thực tài khoản người dân
                    </h3>
                </div>
                <div class="block-content padding-tb-05">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <p>Nhập mã xác thực đã gửi qua email đã đăng ký</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'maxacthuc')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= Html::submitButton('Xác thực tài khoản', ['class' => 'btn btn-success']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>

            </div>
        </div>
    </div>
</div>