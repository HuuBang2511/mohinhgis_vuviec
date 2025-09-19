<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;
$requestedAction = Yii::$app->requestedAction;
$controller = $requestedAction->controller;

$this->title = Yii::t('app', $controller->title);
?>

<div class="row">
    <div class="offset-lg-3 col-lg-6">
        <div class="block block-themed">
            <div class="block-header">
                <h3 class="block-title">
                    <?= $this->title ?>
                </h3>
            </div>
            <div class="block-content">
                <?php $form = ActiveForm::begin() ?>
                <div class="row">
                    <div class="col-lg-12">
                        <?php $successpass = Yii::$app->session->getFlash('updatedpass') ?>
                        <?php if (isset($successpass)) : ?>
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <div class="flex-00-auto">
                                    <i class="fa fa-fw fa-check"></i>
                                </div>
                                <div class="flex-fill ml-3">
                                    <p class="mb-0">Cập nhật mật khẩu thành công!</p>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php $error = Yii::$app->session->getFlash('error_password') ?>
                        <?php if (isset($error)) : ?>
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <div class="flex-00-auto">
                                    <i class="fa fa-fw fa-warning"></i>
                                </div>
                                <div class="flex-fill ml-3">
                                    <p class="mb-0">Mật khẩu cũ không đúng!</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <?= $form->field($authChangePass, 'password')->input('password') ?>
                        <?= $form->field($authChangePass, 'newpassword')->input('password') ?>
                        <?= $form->field($authChangePass, 'confirm')->input('password') ?>
                    </div>
                </div>
                <div class="row pb-3">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <?= Html::submitButton('Đổi mật khẩu', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?php ActiveForm::end() ?>
    </div>
</div>
