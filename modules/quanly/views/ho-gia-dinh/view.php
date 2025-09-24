<?php

use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\crud\CrudAsset;
use app\modules\services\UtilityService;
use kartik\detail\DetailView;

CrudAsset::register($this);

$requestedAction = Yii::$app->requestedAction;
$controller = $requestedAction->controller;
$const['label'] = $controller->const['label'];

$this->title = Yii::t('app', $const['label'][$requestedAction->id] . ' ' . $controller->const['title']);
$this->params['breadcrumbs'][] = ['label' => $const['label']['index'] . ' ' . $controller->const['title'], 'url' => $controller->const['url']['index']];
$this->params['breadcrumbs'][] = $const['label']['view'];
?>


<div class="row">
    <div class="col-lg-12">
        <div class="block block-themed">
            <div class="block-header">
                <h3 class="block-title">Thông tin hộ gia đình</h3>
                <div class="block-options">
                    <?= Html::a('Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
                </div>
            </div>
            <div class="d-lg-none py-2 px-2">
                <div class="row">
                    <div class="col-lg-12">
                        <button type="button" class="btn w-100 btn-primary d-flex justify-content-between align-items-center" data-toggle="class-toggle" data-target="#tabs-navigation" data-class="d-none">
                            Menu
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div id="tabs-navigation" class="d-none d-lg-block">
                <ul class="nav nav-tabs nav-tabs-block" role="tablist">
                    <li class="nav-item" role="presentation">
                        <?= Html::button('Thông tin hộ gia đình', [
                            'type' => 'button',
                            'class' => 'nav-link active',
                            'data-bs-toggle' => 'tab',
                            'href' => "#thongtinhogiadinh-view",
                        ]) ?>
                    </li>
                    <li class="nav-item" role="presentation">
                        <?= Html::button('Công dân thuộc hộ gia đình', [
                            'type' => 'button',
                            'class' => 'nav-link',
                            'data-bs-toggle' => 'tab',
                            'href' => "#thongtincongdan-view",
                        ]) ?>
                    </li>
                </ul>
            </div>

            <div class="block-content tab-content">
                <div class="tab-pane active" id="thongtinhogiadinh-view">
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width:35%"><?= $model->getAttributeLabel('ma_hsct')?></th>
                                    <td><?= $model->ma_hsct?></td>
                                </tr>
                            
                                <tr>
                                    <th style="width:35%">Địa chỉ nóc gia</th>
                                    <td><?= ($model->nocgia_id != null) ? $model->nocgia->so_nha.', '.$model->nocgia->ten_duong.', '.$model->nocgia->phuongxa->tenXa : '' ?></td>
                                </tr>

                                <tr>
                                    <th style="width:35%">Chủ hộ</th>
                                    <td><?= ($model->chuho != null) ? $model->chuho->ho_ten : '<i>Chưa có</i>' ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>


                <div class="tab-pane" id="thongtincongdan-view">
                    <?php if (isset($congdans)) : ?>
                        <a href="<?= Yii::$app->homeUrl ?>quanly/nguoi-dan/create?id=<?= $_GET['id'] ?>"
                                class="btn  btn-success mb-3 float-end">Thêm mới người dân vào hộ</a>
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>STT</th>
                                <th>Họ tên</th>
                                <th>Số điện thoại</th>
                                <th>Giới tính</th>
                                <th>CCCD</th>
                                <th>Thao tác</th>
                            </tr>
                            <?php if ($congdans != null) : ?>
                                <?php foreach ($congdans as $i => $congdan) : ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= $congdan->ho_ten ?></td>
                                        <td><?= $congdan->so_dien_thoai ?></td>
                                        <td><?= ($congdan->gioitinh_id != null) ? $congdan->gioitinh->ten : '' ?></td>
                                        <td><?= $congdan->cccd ?></td>
                                        <td class="text-center">
                                            <a class="btn btn-sm btn-primary" href="<?= Yii::$app->urlManager->createUrl(['quanly/nguoi-dan/view','id' => $congdan->id]) ?>"><i class="fa fa-eye"></i></a>
                                            <!-- <a class="btn btn-sm btn-danger" href="<?= Yii::$app->homeUrl ?>administration/nocgia/delete-hogiadinh?id=<?= $congdan->id ?>" data-confirm="Xóa thông hộ gia khỏi nóc gia?"><i class="fa fa-trash"></i></a> -->
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
            <div class="block-content">
                <div class="row px-3 py-3">
                    <div class="col-lg-12 form-group">
                        <a href="javascript:history.back()" class="btn btn-light float-end"><i class="fa fa-arrow-left"></i>
                            Quay lại</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "size" => Modal::SIZE_EXTRA_LARGE,
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>

