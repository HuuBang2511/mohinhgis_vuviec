<?php

use yii\helpers\Html;
use yii\helpers\Url;

return [

    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'username',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'email',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fullname',
    ],

    [
        'class' => '\kartik\grid\BooleanColumn',
        'attribute' => 'active',
        'showNullAsFalse' => true,
        'trueLabel' => 'Hoạt động',
        'falseLabel' => 'Đã khóa'
    ],
    // TODO hiện avatar khi làm xong chức năng upload avatar
    // [
    //     'attribute' => 'avatar', 
    //     'value' => function ($model) {
    //         return Html::img($model->avatar, ['width' => '40px']);
    //     },
    //     'format' => 'raw'
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'template' => '{view} {cap-nhat-tai-khoan} {lock}{unlock} {update-pass} {delete}',
        'buttonOptions' => [
            'view' => ['icon' => 'fa fa-lock']
        ],
        'width' => '20%',
        'buttons' => [
            'lock' => function ($url, $model) {
                if ($model->active) {
                    return Html::a(
                        "<span class='fa fa-lock'></span>",
                        $url,
                        [
                            'class' => 'btn btn-warning btn-sm',
                            'title' => 'Khoá tài khoản',
                            'data' => [
                                'confirm' => 'Bạn có chắc muốn khóa tài này?',
                                'method' => 'post',
                            ],
                        ]
                    );
                }
            },
            'unlock' => function ($url, $model) {
                if (!$model->active) {
                    return  Html::a(
                        "<span class='fa fa-unlock'></span>",
                        $url,
                        [
                            'class' => 'btn btn-success btn-sm',
                            'title' => 'Mở khóa tài khoản',
                            'data' => [
                                'confirm' => 'Bạn có chắc muốn mở khóa tài này?',
                                'method' => 'post',
                            ],
                        ]
                    );
                }
            },
            'update-pass' => function ($url, $model) {
                return Html::a(
                    "<span class='fa fa-key'></span>",
                    $url,
                    [
                        'class' => 'btn btn-primary btn-sm',
                        'title' => 'Cập nhật mật khẩu',
                        'data-pjax' => '0'
                    ]
                );
            },
            'view' => function ($url, $model) {
                return Html::a('<span class="fas fa-eye"></span>', $url, [
                    'title' => Yii::t('app', 'View'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-info btn-sm'
                ]);
            },
            'cap-nhat-tai-khoan' => function ($url, $model) {
                return Html::a('<span class="fas fa-edit"></span>', $url, [
                    'title' => Yii::t('app', 'Update'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-warning btn-sm'
                ]);
            },
            'delete' => function ($url, $model) {
                return Html::a('<span class="fas fa-trash-alt"></span>', $url, [
                    'title' => Yii::t('app', 'Delete'),
                    'data-pjax' => '0',
                    'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'class' => 'btn btn-danger btn-sm'
                ]);
            },
        ],
    ],
];
