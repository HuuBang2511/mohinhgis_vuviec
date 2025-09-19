<?php 

namespace app\views\layouts\map;

class menulist{

    public static $adminSidebar = [
        'bando' => [
            'name' => 'Bản đồ',
            'icon' => 'fa fa-map-marked-alt',
            'url' => '/map-qlkt'
        ],
        'donvikinhte' =>  [
            'name' => 'Đơn vị kinh tế',
            'icon' => 'fa fa-list',
            'url' => '',
            'items' => [
                    
                [
                    'name' => 'Tìm kiếm đơn vị kinh tế',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/don-vi-kinh-te/index'
                ],            
                [
                    'name' => 'Thêm mới đơn vị kinh tế',
                    'icon' => 'fa fa-plus',
                    'url' => '/quanly/don-vi-kinh-te/create'
                ],
                [
                    'name' => 'Kiểm tra điều kiện đăng ký kinh doanh',
                    'icon' => 'fa fa-plus',
                    'url' => '/quanly/kiem-tra-dieu-kien-kinh-doanh/index'
                ],
                [
                    'name' => 'divider',
                ],
                [
                    'name' => 'Doanh nghiệp',
                    'icon' => 'fa fa-briefcase',
                    'url' => 'quanly/don-vi-kinh-te/index?ten=&so=&masothue=&so_nha=&ten_duong=&phuongxa=&loaidonvikinhte%5B0%5D=1&tinhtranghoatdong=&linhvuc=&nganhnghe_hoatdong=&loai_dkkd=1&ngaycap_khoang=&ngaycaplai_khoang=&vondieule_khoang=0%2C2000000000&loaihinhdoanhnghiep=&loaidoanhnghiep=&nganhnghe='
                ],
                [
                    'name' => 'Hộ kinh doanh',
                    'icon' => 'fa fa-house',
                    'url' => 'quanly/don-vi-kinh-te/index?ten=&so=&masothue=&so_nha=&ten_duong=&phuongxa=&loaidonvikinhte%5B0%5D=2&tinhtranghoatdong=&linhvuc=&nganhnghe_hoatdong=&loai_dkkd=1&ngaycap_khoang=&ngaycaplai_khoang=&vondieule_khoang=0%2C2000000000&loaihinhdoanhnghiep=&loaidoanhnghiep=&nganhnghe='
                ],
                [
                    'name' => 'Danh sách đơn vị kinh tế vừa thêm mới',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/don-vi-kinh-te/danhsach-themmoi?sort=created_at'
                ],
                [
                    'name' => 'Danh sách đơn vị kinh tế vừa thay đổi',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/don-vi-kinh-te/danhsach-capnhap?sort=updated_at'
                ],
                [
                    'name' => 'Danh sách đơn vị kinh tế đã xóa',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/don-vi-kinh-te/danhsach-daxoa'
                ],
                [
                    'name' => 'Danh sách đơn vị kinh tế chưa có tọa độ',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/don-vi-kinh-te/danhsach-chuamap'
                ],
                [
                    'name' => 'Danh sách đơn vị kinh tế là chợ',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/don-vi-kinh-te/danhsach-cho'
                ],
                [
                    'name' => 'Danh sách đơn vị kinh tế không phải là chợ',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/don-vi-kinh-te/danhsach-khongcho'
                ],
                [
                    'name' => 'Danh sách rà soát đơn vị kinh tế',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/don-vi-kinh-te/danhsach-kiemtra-tinhtrang'
                ],
                [
                    'name' => 'Import đơn vị kinh tế',
                    'icon' => 'fa-solid fa-file-import',
                    'url' => 'quanly/don-vi-kinh-te/import'
                ],
                [
                    'name' => 'divider',
                ],
            ]
        ],
        'Thông tin liên quan' => [
            'name' => 'Thông tin liên quan',
            'icon' => 'fa fa-list',
            'url' => '',
            'items' => [
                [
                    'name' => 'Giấy đủ điều kiện',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/donvikinhte-dudieukien/index'
                ],
                [
                    'name' => 'Import giấy đủ điều kiện',
                    'icon' => 'fa-solid fa-file-import',
                    'url' => 'quanly/donvikinhte-dudieukien/import'
                ],
                [
                    'name' => 'divider',
                ],
                [
                    'name' => 'Thông tin thuế',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/thong-tin-thue/index'
                ],
                [
                    'name' => 'Bộ thuế theo năm',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/bothue-theonam/index'
                ],
                [
                    'name' => 'Import thông tin thuế',
                    'icon' => 'fa-solid fa-file-import',
                    'url' => 'quanly/thong-tin-thue/import'
                ],
                [
                    'name' => 'divider',
                ],
                [
                    'name' => 'Hậu kiểm',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/thongtin-kiemtra/index'
                ],
                [
                    'name' => 'Import thông tin hậu kiểm',
                    'icon' => 'fa-solid fa-file-import',
                    'url' => 'quanly/thongtin-kiemtra/import'
                ],
                [
                    'name' => 'divider',
                ],
                [
                    'name' => 'Vi phạm',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/thongtin-vipham/index'
                ],
                [
                    'name' => 'Import vi phạm',
                    'icon' => 'fa-solid fa-file-import',
                    'url' => 'quanly/thongtin-vipham/import'
                ],
                [
                    'name' => 'divider',
                ],
                [
                    'name' => 'Thành tích, khen thưởng',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/thongtin-khenthuong/index'
                ],
                [
                    'name' => 'Import thành tích, khen thưởng',
                    'icon' => 'fa-solid fa-file-import',
                    'url' => 'quanly/thongtin-khenthuong/import'
                ],
                [
                    'name' => 'divider',
                ],
                [
                    'name' => 'Thông tin lao động',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/thongtin-laodong/index'
                ],
                [
                    'name' => 'Import thông tin lao động',
                    'icon' => 'fa-solid fa-file-import',
                    'url' => 'quanly/thongtin-laodong/import'
                ],
            ]
        ],
        'danhmuc' => [
            'name' => 'Danh mục',
            'icon' => 'fa fa-list',
            'url' => '',
            'items' => [
                [
                    'name' => 'Dân tộc',
                    'icon' => 'fa fa-list',
                    'url' => '/danhmuc/dan-toc/'
                ],
                [
                    'name' => 'Giới tính',
                    'icon' => 'fa fa-list',
                    'url' => '/danhmuc/gioi-tinh/'
                ],
                [
                    'name' => 'Lĩnh vực',
                    'icon' => 'fa fa-list',
                    'url' => '/danhmuc/linh-vuc/'
                ],
                [
                    'name' => 'Quy mô doanh nghiệp',
                    'icon' => 'fa fa-list',
                    'url' => '/danhmuc/loai-doanh-nghiep/'
                ],
                [
                    'name' => 'Loại đơn vị kinh tế',
                    'icon' => 'fa fa-list',
                    'url' => '/danhmuc/loai-don-vi-kinh-te/'
                ],
                [
                    'name' => 'Loại giấy đủ điều kiện',
                    'icon' => 'fa fa-list',
                    'url' => '/danhmuc/giay-du-dieu-kien/'
                ],
                [
                    'name' => 'Nhóm loại giấy chứng nhận đăng ký',
                    'icon' => 'fa fa-list',
                    'url' => '/danhmuc/nhom-loaigiayphep'
                ],
                [
                    'name' => 'Loại giấy tờ chứng thực',
                    'icon' => 'fa fa-list',
                    'url' => '/danhmuc/loai-giay-to/'
                ],
                [
                    'name' => 'Loại hình khen thưởng',
                    'icon' => 'fa fa-list',
                    'url' => '/danhmuc/loai-khen-thuong/'
                ],
                [
                    'name' => 'Loại hình doanh nghiệp',
                    'icon' => 'fa fa-list',
                    'url' => '/danhmuc/loai-hinh-doanh-nghiep/'

                ],
                
                [
                    'name' => 'Ngành nghề',
                    'icon' => 'fa fa-list',
                    'url' => '/danhmuc/nganh-nghe/'

                ],
                
                [
                    'name' => 'Quốc tịch',
                    'icon' => 'fa fa-list',
                    'url' => '/danhmuc/quoc-tich/'

                ],                
                [
                    'name' => 'Trạng thái mã số thuế',
                    'icon' => 'fa fa-list',
                    'url' => '/danhmuc/tinh-trang-hoat-dong/'

                ],
                [
                    'name' => 'Tình trạng hoạt động theo phường',
                    'icon' => 'fa fa-list',
                    'url' => '/danhmuc/tinhtranghoatdong-theophuong/'

                ],
                [
                    'name' => 'Chuyên ngành quản lý',
                    'icon' => 'fa fa-list',
                    'url' => '/danhmuc/chuyen-nganh-quan-ly/'

                ],
            ],
        ], 
        'thongke' => [
            'name' => 'Báo cáo, Thống kê',
            'icon' => 'fa fa-bar-chart',
            'items' => [
                [
                    'name' => 'Tổng hợp số liệu',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/don-vi-kinh-te/statistic'
                ],
                [
                    'name' => 'Cấp mới giấy CNĐK',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/don-vi-kinh-te/thongke-dkkd?so_nha=&ten_duong=&phuongxa=&so=&loaidonvikinhte=&tinhtranghoatdong=&linhvuc=&nganhnghe_hoatdong=&loai_dkkd=1&ngaycap_khoang=&ngaycaplai_khoang=&vondieule_khoang=0%2C2000000000&loaihinhdoanhnghiep=&loaidoanhnghiep='
                ],
                [
                    'name' => 'Thay đổi giấy CNĐK',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/don-vi-kinh-te/statistic'
                ],
                [
                    'name' => 'Cấp lại giấy CNĐK',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/don-vi-kinh-te/thongke-dkkd?so_nha=&ten_duong=&phuongxa=&so=&loaidonvikinhte=&tinhtranghoatdong=&linhvuc=&nganhnghe_hoatdong=&loai_dkkd=2&ngaycap_khoang=&ngaycaplai_khoang=&vondieule_khoang=0%2C2000000000&loaihinhdoanhnghiep=&loaidoanhnghiep='
                ],
                [
                    'name' => 'Thu hồi giấy CNĐK',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/don-vi-kinh-te/statistic'
                ],
                [
                    'name' => 'Ngưng hoạt động',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/don-vi-kinh-te/statistic??ten=&so=&masothue=&so_nha=&ten_duong=&phuongxa=&loaidonvikinhte=&tinhtranghoatdong%5B0%5D=6&linhvuc=&nganhnghe_hoatdong=&loai_dkkd=1&ngaycap_khoang=&ngaycaplai_khoang=&vondieule_khoang=0%2C2000000000&loaihinhdoanhnghiep=&loaidoanhnghiep=&nganhnghe='
                ],
                [
                    'name' => 'Tạm ngưng hoạt động',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/don-vi-kinh-te/statistic?ten=&so=&masothue=&so_nha=&ten_duong=&phuongxa=&loaidonvikinhte=&tinhtranghoatdong%5B0%5D=7&linhvuc=&nganhnghe_hoatdong=&loai_dkkd=1&ngaycap_khoang=&ngaycaplai_khoang=&vondieule_khoang=0%2C2000000000&loaihinhdoanhnghiep=&loaidoanhnghiep=&nganhnghe='
                ],
                [
                    'name' => 'Lũy kế',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/don-vi-kinh-te/statistic'
                ],
                [
                    'name' => 'Lĩnh vực theo năm',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/don-vi-kinh-te/thongke-linhvuc-theonam'
                ],
                [
                    'name' => 'Thông tin vi phạm',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/thongtin-vipham/statistic'
                ],
                [
                    'name' => 'Thông tin thành tích, khen thưởng',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/thongtin-khenthuong/statistic',
                ],
                [
                    'name' => 'Thông tin lao động theo thời gian',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/thongtin-laodong/thongke-nam',
                ],
                [
                    'name' => 'Thông tin lao động',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/thongtin-laodong/statistic',
                ],
                [
                    'name' => 'Thông tin thuế',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/thong-tin-thue/statistic'
                ],
                [
                    'name' => 'Thông tin thuế theo thời gian',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/thong-tin-thue/thongke-nam'
                ],
                [
                    'name' => 'Thông tin thuế lũy kế theo bộ thuế',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/bothue-theonam/thongke-luyke'
                ],
                [
                    'name' => 'Thông tin giấy đủ điều kiện',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/donvikinhte-dudieukien/statistic'
                ],
                [
                    'name' => 'Thông tin hậu kiểm',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/thongtin-kiemtra/statistic'
                ],
                [
                    'name' => 'Thông tin người đại diện',
                    'icon' => 'fa fa-list',
                    'url' => '/quanly/nguoi-dai-dien/statistic'
                ],
            ],
        ],
    ];
}
?>