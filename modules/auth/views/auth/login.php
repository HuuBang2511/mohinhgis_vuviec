<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\AppAsset;
use hcmgis\user\assets\UserAsset;

UserAsset::register($this);

$this->title = 'Đăng nhập';
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
        body {
            background: url('<?= Yii::$app->request->baseUrl ?>/images/background_thanhtra_moi.png') no-repeat center center fixed;
            background-size: cover;
        }
        .login-card {
            max-width: 420px;
            margin: auto;
            margin-top: 8%;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
        }
        .logo {
            max-width: 100px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<?php $this->beginBody() ?>

<div class="container">
    <div class="card login-card bg-white p-4">
        <div class="text-center">
            <img src="<?= Yii::$app->request->baseUrl ?>/images/logo_thanhtra.jpg" alt="Logo" class="logo">
            <h4 class="mb-3">Đăng nhập</h4>
        </div>

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <?= $form->field($model, 'username')->textInput([
                'placeholder' => 'Tên đăng nhập',
                'class' => 'form-control form-control-lg'
            ]) ?>

            <?= $form->field($model, 'password')->passwordInput([
                'placeholder' => 'Mật khẩu',
                'class' => 'form-control form-control-lg'
            ]) ?>

            <div class="form-check mb-3">
                <?= $form->field($model, 'rememberMe')->checkbox([
                    'class' => 'form-check-input',
                    'labelOptions' => ['class' => 'form-check-label'],
                ]) ?>
            </div>

            <button type="submit" class="btn btn-primary w-100 btn-lg mb-2">
                <i class="fa fa-sign-in-alt me-2"></i> Đăng nhập
            </button>
            <a href="<?= Yii::$app->urlManager->createUrl(['auth/user/tao-tai-khoan-nguoi-dan']) ?>" class="btn btn-success w-100 btn-lg">
                <i class="fa fa-user-plus me-2"></i> Người dân đăng ký tài khoản
            </a>
        <?php ActiveForm::end(); ?>

        <?php if (\Yii::$app->has('authClientCollection')): ?>
            <hr>
            <p class="text-center mb-3">Hoặc đăng nhập bằng:</p>
            <div class="d-flex justify-content-center gap-2">
                <?= \hcmgis\contrib\oauth2\AuthChoice::widget([
                    'baseAuthUrl' => ['auth/auth']
                ]) ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="text-center text-white mt-4">
        <small>© <span id="year"></span> HCMGIS</small>
    </div>
</div>

<script>
    document.getElementById("year").innerHTML = new Date().getFullYear();
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
