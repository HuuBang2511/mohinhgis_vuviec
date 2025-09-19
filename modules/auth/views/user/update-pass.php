<?php


use kartik\form\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;

$this->title = (isset($const['title'])) ? $const['title'] : 'Thay đổi mật khẩu';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

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

<div class="block block-themed ">
    <div class="block-header block-header-default">

        <h3 class="block-title text-uppercase">
            <i class="fa fa-info-circle"></i> Cập nhật mật khẩu
        </h3>

    </div>
    <div class="block-content block-content-full bg-image text-center">
        <img class="img-avatar img-avatar96 img-avatar-thumb" src="<?= $model['tai-khoan']->getAvatarUrl() ?>" alt="">
        <h3 class="my-2 "><?= $model['tai-khoan']->fullname ?></h3>
    </div>

    <div class="block-content block-content-full">

        <?= $form->field($model['doimatkhau'], 'newpassword')->input('password')->label('Mật khẩu mới') ?>


        <div class=" text-center">
            <?= Html::submitButton('Cập nhật', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>




<?php ActiveForm::end() ?>