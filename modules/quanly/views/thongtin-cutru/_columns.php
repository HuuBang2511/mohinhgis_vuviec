<?php
use yii\helpers\Url;
use yii\helpers\Html;

return [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nguoidan_id',
        'label' => 'Công dân',
        'format' => 'raw',
        'value' => function($model) {
            if (!$model->nguoidan) return null;
            $cd = $model->nguoidan;
            $gioitinh = isset($cd->gioitinh) ? $cd->gioitinh->ten : '';
            return Html::tag('div',
                Html::tag('b', $cd->ho_ten) . '<br>' .
                'Ngày sinh: ' . $cd->ngaysinh . '<br>' .
                'Điện thoại: ' . $cd->so_dien_thoai . '<br>' .
                'Giới tính: ' . $gioitinh . '<br>' .
                'CCCD: ' . $cd->cccd
            );
        },
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'ngaybatdau',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'ngayketthuc',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'loaicutru_id',
        'value' => 'loaicutru.ten'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nguoidan_id',
        'value' => 'nguoidan.ho_ten'
    ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'diachi_thuongtru',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'diachi_cutru',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'diachi_tamtru',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'status',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'created_at',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'updated_at',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'created_by',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'updated_by',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'width' => '180px',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'buttons' => [
            'view' => function ($url, $model, $key) {
                return Html::a('<span class="fa fa-info"></span>',$url,['class' => 'btn btn-info btn-sm','title'=>'Xem']);
            },
            'update' => function ($url, $model, $key) {
                return Html::a('<span class="fa fa-pen"></span>',$url,['class' => 'btn btn-warning btn-sm','title'=>'Cập nhật']);
            },
            'delete' => function ($url, $model, $key) {
                return Html::a('<span class="fa fa-trash"></span>',$url,['class' => 'btn btn-danger btn-sm','role' => 'modal-remote','title'=>'Xóa']);
            },
        ],
    ],

];   