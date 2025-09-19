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

$this->title = (isset($const['title'])) ? $const['title'] : 'Thêm mới tài khoản quản trị viên phường';
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
                            <?= $form->field($model, 'phuongxa_id')->widget(Select2::className(), [
                                'data' => ArrayHelper::map($categories['phuongxa'],'id','ten'),
                                'options' => ['id' => 'phuongxa-id', 'prompt' => 'Chọn phường', 'disabled' => (Yii::$app->user->identity->is_admin && Yii::$app->user->identity->admin_phuong_id != 0) ? true : false],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]) ?>
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