<?php
/* @var $this View */
/* @var $content string */

use app\modules\contrib\widgets\FlashMessageWidget;
use hcmgis\user\assets\UserAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

$siteName = ArrayHelper::getValue(Yii::$app->params, 'siteName', 'siteName');
UserAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <link rel="shortcut icon" href="<?= Yii::$app->homeUrl ?>resources/images/favicon.ico" type="image/x-icon">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= Yii::$app->homeUrl ?>resources/images/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= Yii::$app->homeUrl ?>resources/images/favicon.ico">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= $siteName ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <script>
        // TODO: redirect http to https
        if (window.location.protocol == 'http:' && window.location.hostname != 'localhost') {
            window.location.assign('https://' + window.location.hostname + window.location.pathname + window.location.search);
        }
    </script>
    <?php $this->head() ?>

</head>

<body cz-shortcut-listen="true" infinite-wrapper>

    <?php $this->beginBody() ?>

    <div id="page-container" >

        
        <main id="main-container">
            <?= $content ?>
        </main>
        

    </div>
    <?php $this->endBody() ?>

</body>

</html>
<?php $this->endPage(); ?>