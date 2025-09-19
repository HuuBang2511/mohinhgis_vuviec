<?php

namespace app\modules;


use app\widgets\maps\layers\TileLayer;

class APPConfig
{
    public function convertRoute($route)
    {
        return '.' . str_replace('/', '.', $route) . '.index';
    }

    public static $SITENAME = 'GIS - Cấp nước';
    public static $CONFIG = [
        'adminSidebar' => [
            [
                'name' => 'Quản lý người dùng',
                'icon' => 'fa fa-users',
                'url' => '/auth/user/index',
                'key' => '.auth.user.index',
                'hasChild' => false,
            ],
            [
                'name' => 'Quản lý nhóm quyền',
                'icon' => 'fa fa-th-list',
                'url' => '/user/auth-group',
                'key' => '.user.auth-group.index',
                'hasChild' => false,
            ],
            [
                'name' => 'Quản lý quyền truy cập',
                'icon' => 'fa fa-th-list',
                'url' => '/user/auth-role',
                'key' => '.user.auth-role.index',
                'hasChild' => false,
            ],
            [
                'name' => 'Quản lý hoạt động',
                'icon' => 'fa fa-th-list',
                'url' => '/user/auth-action',
                'key' => '.user.auth-action.index',
                'hasChild' => false,
            ],
        ],
        // 'vientham' => [
        //     [
        //         'name' => 'Kết quả phân tích',
        //         'icon' => 'fa fa-list',
        //         'url' => 'quanly/ketqua-vientham',
        //         'key'=>'quanly.ketqua-vientham.index',
        //         'hasChild' => false,
        //     ],
        //     [
        //         'name' => 'Module Viễn thám',
        //         'icon' => 'fa fa-list',
        //         'url' => 'quanly/anhvientham',
        //         'key'=>'quanly.anhvientham.index',
        //         'hasChild' => false,
        //     ]
        // ],
//         'aphu' => [
//             [
//                 'name' => 'Đồng hồ KH',
//                 'icon' => 'fa fa-list',
//                 'url' => 'quanly/aphu/dongho-kh',
//                 'key'=>'quanly.aphu/dongho-kh.index',
//                 'hasChild' => false,
//             ],
// //            [
// //                'name' => 'Hồ Thủy Lợi',
// //                'icon' => 'fa fa-list',
// //                'url' => 'quanly/aphu/ho-thuyloi',
// //                'key'=>'quanly.aphu/ho-thuyloi.index',
// //                'hasChild' => false,
// //            ],
//             [
//                 'name' => 'Nhà máy nước',
//                 'icon' => 'fa fa-list',
//                 'url' => 'quanly/aphu/nhamay-nuoc',
//                 'key'=>'quanly.aphu/nhamay-nuoc.index',
//                 'hasChild' => false,
//             ],
//             [
//                 'name' => 'Ống dịch vụ',
//                 'icon' => 'fa fa-list',
//                 'url' => 'quanly/aphu/ong-dichvu',
//                 'key'=>'quanly.aphu/ong-dichvu.index',
//                 'hasChild' => false,
//             ],
//             [
//                 'name' => 'Ống nước thô',
//                 'icon' => 'fa fa-list',
//                 'url' => 'quanly/aphu/ong-nuoctho',
//                 'key'=>'quanly.aphu/ong-nuoctho.index',
//                 'hasChild' => false,
//             ],
//             [
//                 'name' => 'Ống phân phối',
//                 'icon' => 'fa fa-list',
//                 'url' => 'quanly/aphu/ong-phanphoi',
//                 'key'=>'quanly.aphu/ong-phanphoi.index',
//                 'hasChild' => false,
//             ],
//             [
//                 'name' => 'Van mạng lưới',
//                 'icon' => 'fa fa-list',
//                 'url' => 'quanly/aphu/van-mangluoi',
//                 'key'=>'quanly.aphu/van-mangluoi.index',
//                 'hasChild' => false,
//             ],
//         ],
        'quanly' => [
            [
                'name' => 'Vụ việc',
                'icon' => 'fa fa-list',
                'url' => 'quanly/vu-viec',
                'key'=>'quanly.vu-viec.index',
                'hasChild' => false,
            ],
            [
                'name' => 'Đơn vị',
                'icon' => 'fa fa-list',
                'url' => 'quanly/don-vi',
                'key'=>'quanly.don-vi.index',
                'hasChild' => false,
            ],
            [
                'name' => 'Cán bộ',
                'icon' => 'fa fa-list',
                'url' => 'quanly/can-bo',
                'key'=>'quanly.can-bo.index',
                'hasChild' => false,
            ],
        ],
        'map' => [
            // [
            //     'name' => 'Cấp nước Đức Trọng',
            //     'icon' => 'fa fa-list',
            //     'url' => 'quanly/map/ductrong',
            //     'key'=>'quanly.map.ductrong',
            //     'hasChild' => false,
            // ],
            [
                'name' => 'Bản đồ điểm nóng',
                'icon' => 'fa fa-map',
                'url' => 'quanly/map',
                'key'=>'quanly.map.index',
                'hasChild' => false,
            ]
        ],
        'danhmuc' => [
            [
                'name' => 'Lĩnh vực',
                'icon' => 'fa fa-list',
                'url' => 'quanly/linh-vuc',
                'key'=>'quanly.linhvuc.index',
                'hasChild' => false,
            ],
            [
                'name' => 'Trạng thái',
                'icon' => 'fa fa-list',
                'url' => 'quanly/trang-thai-xu-ly',
                'key'=>'quanly.trang-thai-xu-ly.index',
                'hasChild' => false,
            ]
        ],

    ];

    public static $ROOT_URL = 'app/';
    public static $URL_KEY = 'hcdcythd2022';
//    public static $HCMGIS_MAP = 'https://thuduc-maps.hcmgis.vn/thuducserver/gwc/service/wmts?layer=thuduc:thuduc_maps&style=&tilematrixset=EPSG:900913&Service=WMTS&Request=GetTile&Version=1.0.0&Format=image/png&TileMatrix=EPSG:900913:{z}&TileCol={x}&TileRow={y}';

    public static $BASEMAP = [
        'GoogleMap' => [
            'urlTemplate' => 'http://{s}.google.com/vt/lyrs=r&x={x}&y={y}&z={z}',
            'layerName' => 'Google Map',
            'clientOptions' => [
                'attribution' => '© GoogleMap contributors',
                'maxZoom' => 24,
                'subdomains' => ['mt0', 'mt1', 'mt2', 'mt3']
            ],
        ],
        'GoogleEarth' => [
            'urlTemplate' => 'http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}',
            'layerName' => 'Ảnh vệ tinh',
            'clientOptions' => [
//                'attribution' => '© GoogleMap contributors',
                'maxZoom' => 24,
                'subdomains' => ['mt0', 'mt1', 'mt2', 'mt3']
            ],
        ],
//        'OpenStreetMap' => [
//            'urlTemplate' => 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
//            'layerName' => 'OSM',
//            'clientOptions' => [
//                'attribution' => '© OpenStreetMap contributors',
//                'maxZoom' => 22,
//            ],
//        ],

    ];

    public static function getUrl($url)
    {
        return \Yii::$app->homeUrl . self::$ROOT_URL . $url;
    }
}