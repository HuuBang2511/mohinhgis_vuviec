<?php

use yii\bootstrap5\BootstrapAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap4\Modal;
use app\widgets\crud\CrudAsset;
use app\widgets\gridview\GridView;
use app\widgets\export\ExportMenu;


BootstrapAsset::register($this);
CrudAsset::register($this);

/* @var $this View */
/* @var $searchModel AuthUserSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Tài khoản người dùng';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="diemdanh-index">
    <div id="table-responsive">
        <?php $fullExportMenu = ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => require(__DIR__.'/_columns.php'),
            'target' => ExportMenu::TARGET_BLANK,
            'pjaxContainerId' => 'kv-pjax-container',
            'exportContainer' => [
                'class' => 'btn-group mr-2'
            ],
            'exportConfig' => [
                ExportMenu::FORMAT_TEXT => false,
                ExportMenu::FORMAT_HTML => false,
                ExportMenu::FORMAT_EXCEL => false,
                ExportMenu::FORMAT_PDF => false,
            ],
//            'columnSelectorOptions' => ['class' => 'btn btn-outline-info','label' => 'Chọn cột'],
            'dropdownOptions' => [
                'label' => 'Tải xuống',
                'itemsBefore' => [
                    '<div class="dropdown-header">Xuất tất cả dữ liệu</div>',
                ],
            ],
        ]) ?>
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                $fullExportMenu,
                [
                    'content' =>
                    Html::a('<i class="fa fa-plus"></i> Thêm mới', ['tao-tai-khoan'], ['class' => 'btn btn-success', 'title' => 'Thêm mới', 'data-pjax' => 0]) 
                ],
            ],
            'striped' => true,
            'condensed' => true,
//            'responsive' => false,
            'responsiveWrap' => false,
            'panelPrefix' => 'block ',
            'toolbarContainerOptions' => ['class' => 'float-right'],
            'summaryOptions' => ['class' => 'float-right'],
            'panel' => [
                'type' => 'block-themed',
                'headingOptions' => ['class' => 'block-header'] ,
                'summaryOptions' => ['class' => 'block-options'],
                'titleOptions' => ['class' => 'block-title'] ,
                'heading' => '<i class="fa fa-list"></i> ' .  $this->title ,
            ],
            'tableOptions' => ['class' => 'table table-striped'],
            'layout' => "{items}\n{pager}",
        ])?>
    </div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
  
