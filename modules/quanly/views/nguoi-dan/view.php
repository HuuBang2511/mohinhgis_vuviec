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

<style>
img#img-more {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
    width: 100%;
    max-height: 400px;
    border: solid 1px;
}

img#img-more:hover {
    box-shadow: 0 0 2px 1px rgba(0, 140, 186, 0.5);
}
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="block block-rounded row g-0">
            <ul class="nav nav-tabs nav-tabs-block flex-md-column col-md-3" role="tablist">
                <li class="nav-item d-md-flex flex-md-column" role="presentation">
                    <button class="nav-link text-md-start active" id="thongtinchung-tab" data-bs-toggle="tab"
                        data-bs-target="#thongtinchung" role="tab" aria-controls="thongtinchung" aria-selected="false"
                        tabindex="-1">
                        <i class="fa fa-fw fa-user-circle opacity-50 me-1 d-none d-sm-inline-block"></i> Thông tin công
                        dân
                    </button>
                </li>
                <li class="nav-item d-md-flex flex-md-column" role="presentation">
                    <button class="nav-link text-md-start" id="thongtincutru-tab" data-bs-toggle="tab"
                        data-bs-target="#thongtincutru" role="tab" aria-controls="thongtincutru" aria-selected="true">
                        <i class="fa fa-fw fa-home opacity-50 me-1 d-none d-sm-inline-block"></i> Thông tin cư trú
                    </button>
                </li>

            </ul>
            <div class="tab-content col-md-9">
                <div class="block-content tab-pane active show" id="thongtinchung" role="tabpanel"
                    aria-labelledby="thongtinchung-tab" tabindex="0">
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width:35%">Địa chỉ nóc gia</th>
                                    <td><?= ($model->hogiadinh_id != null) ? $model->hogiadinh->nocgia->so_nha.', '.$model->hogiadinh->nocgia->ten_duong.', '.$model->hogiadinh->nocgia->khupho->TenKhuPho.', '.$model->hogiadinh->nocgia->phuongxa->tenXa : ''?>
                                    </td>
                                </tr>

                                <tr>
                                    <th style="width:35%"><?= $model->getAttributeLabel('ho_ten')?></th>
                                    <td><?= $model->ho_ten?></td>
                                </tr>
                                <tr>
                                    <th style="width:35%"><?= $model->getAttributeLabel('ngaysinh')?></th>
                                    <td><?= $model->ngaysinh?></td>
                                </tr>
                                <tr>
                                    <th style="width:35%"><?= $model->getAttributeLabel('so_dien_thoai')?></th>
                                    <td><?= $model->so_dien_thoai?></td>
                                </tr>

                                <tr>
                                    <th style="width:35%"><?= $model->getAttributeLabel('cccd')?></th>
                                    <td><?= $model->cccd?></td>
                                </tr>
                                <tr>
                                    <th style="width:35%"><?= $model->getAttributeLabel('cccd_ngaycap')?></th>
                                    <td><?= $model->cccd_ngaycap ?></td>
                                </tr>
                                <tr>
                                    <th style="width:35%"><?= $model->getAttributeLabel('cccd_noicap')?></th>
                                    <td><?= $model->cccd_noicap ?></td>
                                </tr>

                                <tr>
                                    <th style="width:35%"><?= $model->getAttributeLabel('gioitinh_id')?></th>
                                    <td><?= ($model->gioitinh_id != null) ? $model->gioitinh->ten : '' ?></td>
                                </tr>

                                <tr>
                                    <th style="width:35%"><?= $model->getAttributeLabel('quanhechuho_id')?></th>
                                    <td><?= ($model->quanhechuho_id != null) ? $model->quanhechuho->ten : '' ?></td>
                                </tr>
                                <tr>
                                    <th style="width:35%"><?= $model->getAttributeLabel('loaicutru_id')?></th>
                                    <td><?= ($model->loaicutru_id != null) ? $model->loaicutru->ten : '' ?></td>
                                </tr>
                            </table>
                            
                            <h4>Hình đính kèm</h4>
                            <div class="row">   
                                <?php
                                    if ($model->url_dinhkem != null) {
                                        $model->url_dinhkem = json_decode($model->url_dinhkem, true);
                                    }
                                    ?>
                                <?php if ($model->url_dinhkem != null): ?>
                                <?php foreach ($model->url_dinhkem as $i => $item): ?>
                                <div class="col-lg-3 mb-3">
                                    <a target="_blank" href="<?= Yii::$app->homeUrl ?><?= $item ?>">
                                        <img id="img-more" src="<?= Yii::$app->homeUrl ?><?= $item ?>" alt="">
                                    </a>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block-content tab-pane" id="thongtincutru" role="tabpanel"
                    aria-labelledby="thongtincutru-tab" tabindex="0">
                    <?php if (isset($thongtinLienquan['cutru'])) : ?>
                    <div class="row">
                        <div class="col-md-12">
                            <?= Html::a('Thêm mới thông tin cư trú', ['thongtin-cutru/create', 'id' => $model->id], ['class' => 'btn btn-success mb-3 float-end']) ?>
                            <table class="table table-bordered">
                                <tr>
                                    <th>STT</th>
                                    <th>Loại cư trú</th>
                                    <th>Thời gian bắt đầu</th>
                                    <th>Thời gian kết thúc</th>
                                    <th>Địa chỉ thường trú</th>
                                    <th>Địa chỉ cư trú</th>
                                    <th>Địa chỉ tạm trú</th>
                                    <th></th>
                                </tr>
                                <?php if ($thongtinLienquan['cutru'] != null) : ?>
                                <?php foreach ($thongtinLienquan['cutru'] as $i => $item) : ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= ($item->loaicutru_id != null) ? $item->loaicutru->ten : '' ?></td>
                                    <td><?= ($item->ngaybatdau != null) ? $item->ngaybatdau : '' ?></td>
                                    <td><?= ($item->ngayketthuc != null) ? $item->ngayketthuc : '' ?></td>
                                    <td><?= ($item->diachi_thuongtru != null) ? $item->diachi_thuongtru : '' ?></td>
                                    <td><?= ($item->diachi_cutru != null) ? $item->diachi_cutru : '' ?></td>
                                    <td><?= ($item->diachi_tamtru != null) ? $item->diachi_tamtru : '' ?></td>
                                    <td class="text-center">
                                        <?= Html::a('<i class="fa fa-eye"></i>', ['thongtin-cutru/view', 'id' => $item->id], ['class' => 'btn btn-block btn-primary ']) ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="8">Không có dữ liệu</td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                    <?php endif ?>
                </div>
            </div>
            <div class="row py-3 pe-0">
                <div class="col-lg-12 form-group">
                    <?= Html::a('Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
                    <a href="javascript:history.back()" class="btn btn-light float-end"><i class="fa fa-arrow-left"></i>
                        Quay lại</a>
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