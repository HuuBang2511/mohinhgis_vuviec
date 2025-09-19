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
                <?php $form = ActiveForm::begin(); ?>
                <div class="row">
                    <div class="col-lg-12">
                        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>


                <div class="row pb-3">
                    <div class="col-lg-12">
                        <?= Html::submitButton('Lưu', ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('<i class="fa fa-chevron-left"></i> Quay lại', 'javascript:history.back()', ['class' => 'btn btn-outline-secondary float-end']) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
