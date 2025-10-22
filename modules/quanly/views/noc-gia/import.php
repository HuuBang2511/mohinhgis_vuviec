<?php

use app\widgets\crud\CrudAsset;
use kartik\file\FileInput;
use kartik\grid\GridView;
use kartik\form\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;

CrudAsset::register($this);
?>

<div class="alert alert-primary alert-dismissible" role="alert">
    <p class="mb-0">Tải file mẫu, và file dữ liệu mẫu để xem mẫu thu thập dữ liệu</p>
    <p class="mb-0">Không sử dụng file dữ liệu mẫu để import</p>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="block block-themed">
            <div class="block-header">
                <h3 class="block-title">Import dân cư</h3>
                <div class="block-options">
                    <a class="btn btn-success" href="<?= Yii::$app->urlManager->createUrl('/uploads/files/form/CAPNHAT_DANCU.xlsx') ?>">
                        <i class="fa fa-download"></i>
                        <span class="title"> File mẫu</span>
                    </a>

                    <a class="btn btn-success" href="<?= Yii::$app->urlManager->createUrl('/uploads/files/form/CAPNHAT_DANCU.xlsx') ?>">
                        <i class="fa fa-download"></i>
                        <span class="title"> File dữ liệu mẫu mẫu</span>
                    </a>
                </div>
            </div>
            <div class="block-content">
                <?php $import = Yii::$app->session->getFlash('noData'); ?>
                <?php if (isset($import)): ?>
                    <div class="alert alert-primary alert-dismissible" role="alert">
                        <p class="mb-0"><?= $import?></p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php $import = Yii::$app->session->getFlash('errorData') ?>
                <?php if (isset($import)): ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <p class="mb-0"><?= $import?></p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php $success = Yii::$app->session->getFlash('uploadSuccess') ?>
                <?php if (isset($success)): ?>
                    <div class="alert alert-primary alert-dismissible" role="alert">
                        <p class="mb-0"><?= $success?></p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
                <div class="row">
                    <div class="col-lg-12">
                        <?= $form->field($fileUpload, 'file')->widget(FileInput::className(), [
                            'pluginOptions' => [
                                'showPreview' => false,
                                'showCaption' => true,
                                'showRemove' => true,
                                'showUpload' => false,
                            ]
                        ])
                        ?>
                    </div>
                </div>

                <div class="row form-group mb-3">
                    <div class="col-lg-12">
                        <?= Html::submitButton('<i class="fa fa-upload"></i> Import',['class' => 'btn btn-success'])?>
                    </div>
                </div>

                <?php ActiveForm::end() ?>
            </div>
        </div>

    </div>
</div>

<?php if($notification != null): ?>
<div class="row">
    <div class="col-lg-12">
        <div class="block block-themed">
            <div class="block-header">
                <h3 class="block-title">Kết quả import</h3>
            </div>
            <div class="block-content pb-3 scroll-tab">
                <div class="row">
                    <div class="col-lg-12">
                        <?php foreach($notification as $i => $item): ?>
                            <span class="<?= $item['style'] ?>"><?= $item['data'].'<br>' ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>        
            </div>
        </div>

    </div>
</div>
<?php endif; ?>

<style>
    .scroll-tab{
        max-height: 500px;
        overflow-y: scroll;
    }
</style>