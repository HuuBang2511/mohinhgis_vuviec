<?php

namespace app\modules\services;

use yii\helpers\Html;

class UserInterfaceServices
{

    const BUTTON_CONTENT = [
        'create' => '<i class="fa fa-plus"></i> Thêm mới',
        'save' => '<i class="fa fa-save"></i> Lưu',
        'update' => '<i class="fa fa-pen"></i> Cập nhật',
        'view' => '<i class="fa fa-info"></i> Chi tiết',
        'delete' => '<i class="fa fa-trash"></i> Xóa',
        'updateList' => '<i class="fa fa-pen"></i>',
        'viewList' => '<i class="fa fa-info"></i>',
        'deleteList' => '<i class="fa fa-trash"></i>',
        'back' => '<i class="fa fa-chevron-left"></i> Quay lại',
    ];

    const BUTTON_OPTIONS = [
        'create' => ['class' => 'btn btn-success', 'data-pjax' => 0],
        'save' => ['class' => 'btn btn-primary', 'data-pjax' => 0],
        'update' => ['class' => 'btn btn-warning', 'data-pjax' => 0],
        'view' => ['class' => 'btn btn-info', 'data-pjax' => 0],
        'delete' => ['class' => 'btn btn-danger', 'data-pjax' => 0],
        'updateList' => ['class' => 'btn btn-warning', 'data-pjax' => 0],
        'viewList' => ['class' => 'btn btn-info', 'data-pjax' => 0],
        'deleteList' => ['class' => 'btn btn-danger', 'data-pjax' => 0],
        'back' => ['class' => 'btn btn-light float-end', 'data-pjax' => 0],];


    public function createButton($type, $content, $url, $options)
    {
        $button = '';
        return $button;
    }

    public static function renderFormFooterButtons()
    {
        $saveButton = Html::submitButton(self::BUTTON_CONTENT['save'],self::BUTTON_OPTIONS['save']);
        $backButton = Html::a(self::BUTTON_CONTENT['back'],"javascript:history.back()",self::BUTTON_OPTIONS['back']);
        return '<div class="row"><div class="col-lg-12 pb-3">' . $saveButton . $backButton . '</div></div>';
    }

    public static function renderViewFooterButtons($modelID)
    {
        $updateButton = Html::a(self::BUTTON_CONTENT['update'],['update','id' => $modelID],self::BUTTON_OPTIONS['update']);
        $backButton = Html::a(self::BUTTON_CONTENT['back'],"javascript:history.back()",self::BUTTON_OPTIONS['back']);
        return '<div class="row"><div class="col-lg-12 pb-3">' . $updateButton . $backButton . '</div></div>';
    }
}