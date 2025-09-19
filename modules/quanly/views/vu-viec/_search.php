<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

?>

<div class="row">
    <div class="col-lg-12">
        <div class="block block-themed">
            <div class="block-header">
                <h5 class="m-0">
                    <i class="fa fa-search light"></i> Tìm kiếm vụ việc
                </h5>
            </div>
            <div class="block-content">
                <?php
                $form = ActiveForm::begin([]);
                ?>
                <div class="row">
                    <div class="col-lg-3">
                        <?= $form->field($searchModel, 'ma_vu_viec')->input('text') ?>
                    </div>
                    <div class="col-lg-3">
                        <?=
                        $form->field($searchModel, 'linh_vuc_id')->widget(Select2::className(), [
                            'data' => ArrayHelper::map($categories['linhvuc'], 'id', 'ten_linh_vuc'),
                            'options' => ['prompt' => 'Chọn lĩnh vực'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>
                    </div>
                    <div class="col-lg-3">
                        <?=
                        $form->field($searchModel, 'trang_thai_hien_tai_id')->widget(Select2::className(), [
                            'data' => ArrayHelper::map($categories['trangthaixuly'], 'id', 'ten_trang_thai'),
                            'options' => ['prompt' => 'Chọn tên trạng thái'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>
                    </div>
                    <div class="col-lg-3">
                        <?=
                        $form->field($searchModel, 'muc_do_canh_bao')->widget(Select2::className(), [
                            'data' => [
                                'Xanh' => 'Xanh',
                                'Vàng' => 'Vàng',
                                'Đỏ' => 'Đỏ'
                            ],
                            'options' => ['prompt' => 'Chọn mức độ cảnh báo'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 pb-3">
                        <div class="float-end">
                            <?= Html::submitButton('Tìm kiếm', ['class' => 'btn btn-info']) ?>
                        </div>
                    </div>
                </div>

                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>