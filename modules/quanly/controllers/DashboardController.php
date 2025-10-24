<?php

namespace app\modules\quanly\controllers;

use app\modules\quanly\base\QuanlyBaseController;
use app\modules\quanly\models\CameraAnNinh;
use app\modules\quanly\models\ChotTuantre;
use app\modules\quanly\models\CosokinhdoanhCodk;
use app\modules\quanly\models\CosonguycoChayno;
use app\modules\quanly\models\DiemNhayCam;
use app\modules\quanly\models\DiemTenannxh;
use app\modules\quanly\models\KhuvucPhuctapAnNinh;
use app\modules\quanly\models\MuctieuTrongdiem;
use app\modules\quanly\models\NguoiDan;
use app\modules\quanly\models\NguonNuocCcc;
// use app\modules\quanly\models\Phuongxa; // Không cần nữa
use app\modules\quanly\models\TrangThaiXuLy;
use app\modules\quanly\models\TruNuocCcc;
use app\modules\quanly\models\VuViec;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * DashboardController (Phiên bản Bảng Điều hành Nghiệp vụ v3)
 * Gỡ bỏ logic lọc theo Phường/Xã.
 * Hệ thống được giả định chạy cho 1 phường duy nhất.
 */
class DashboardController extends QuanlyBaseController
{
    /**
     * Hiển thị Bảng điều hành ANTT thông minh.
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        // --- Lấy ID trạng thái 'Đã giải quyết' ---
        $trangThaiDaGiaiQuyet = TrangThaiXuLy::findOne(['ten_trang_thai' => 'Đã giải quyết']);
        $idDaGiaiQuyet = $trangThaiDaGiaiQuyet ? $trangThaiDaGiaiQuyet->id : -1;

        // === 1. KPI TÁC NGHIỆP CHÍNH (TOÀN HỆ THỐNG - 1 PHƯỜNG) ===
        $kpis = [
            'vuViecHomNay' => (int) VuViec::find()
                ->andWhere(['>=', 'created_at', new Expression('CURRENT_DATE')])
                ->count(),
            
            'canhBaoDoHoatDong' => (int) VuViec::find()
                ->where(['muc_do_canh_bao' => VuViec::CANH_BAO_DO])
                ->andWhere(['!=', 'trang_thai_hien_tai_id', $idDaGiaiQuyet])
                ->count(),
            
            'sapDenHan' => (int) VuViec::find()
                ->where(['!=', 'trang_thai_hien_tai_id', $idDaGiaiQuyet])
                ->andWhere(['BETWEEN', 'han_xu_ly', new Expression('NOW()'), new Expression('NOW() + INTERVAL \'3 days\'')])
                ->count(),
            
            'doiTuongQuanTam' => (int) NguoiDan::find()
                ->where(['!=', 'nhom_doi_tuong', 'Thường'])
                ->count(),
        ];

        // === 2. DANH SÁCH TÁC NGHIỆP (TRUNG TÂM CHỈ HUY) ===
        $topCanhBaoDo = VuViec::find()
            ->where(['muc_do_canh_bao' => VuViec::CANH_BAO_DO])
            ->andWhere(['status' => 1])
            ->andWhere(['!=', 'trang_thai_hien_tai_id', $idDaGiaiQuyet])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(5)->with('linhVuc', 'nguoiDan')
            ->all();

        $topQuaHan = VuViec::find()
            ->where(['<', 'han_xu_ly', date('Y-m-d H:i:s')])
            ->andWhere(['!=', 'trang_thai_hien_tai_id', $idDaGiaiQuyet])
            ->orderBy(['han_xu_ly' => SORT_ASC])
            ->limit(5)->with('trangThaiHienTai', 'canBoTiepNhan')
            ->all();

        // === 3. DỮ LIỆU 6 LỚP CHUYÊN ĐỀ ===
        
        $layerData = [
            // Lớp An ninh
            'anNinh' => [
                'counts' => [
                    'Mục tiêu trọng điểm' => (int) MuctieuTrongdiem::find()->where(['status' => 1])->count(),
                    'Khu vực phức tạp' => (int) KhuvucPhuctapAnNinh::find()->where(['status' => 1])->count(),
                ],
                'list' => KhuvucPhuctapAnNinh::find()
                    ->where(['status' => 1])
                    ->orderBy(['updated_at' => SORT_DESC])
                    ->limit(3)->all(),
                'list_key' => 'ten', // Thuộc tính để hiển thị tên
                'list_badge' => 'muc_do_phuctap' // Thuộc tính để hiển thị badge
            ],
            
            // Lớp Trật tự Xã hội
            'tratTuXaHoi' => [
                'counts' => [
                    'Điểm tệ nạn' => (int) DiemTenannxh::find()->where(['status' => 1])->count(),
                    'Cơ sở KD có ĐK' => (int) CosokinhdoanhCodk::find()->where(['status' => 1])->count(),
                ],
                'list' => CosokinhdoanhCodk::find()
                    ->where(['IN', 'loai_hinh_kinh_doanh', ['Karaoke', 'Bar', 'Khách sạn', 'Nhà nghỉ', 'Cầm đồ']])
                    ->andWhere(['status' => 1])
                    ->orderBy(['updated_at' => SORT_DESC])
                    ->limit(3)->all(),
                'list_key' => 'ten_co_so',
                'list_badge' => 'loai_hinh_kinh_doanh'
            ],

            // Lớp Quản lý Dân cư
            'quanLyDanCu' => [
                'counts' => [
                    'Tổng nhân khẩu' => (int) NguoiDan::find()->where(['status' => 1])->count(),
                    'Đối tượng quan tâm' => $kpis['doiTuongQuanTam'],
                ],
                'list' => NguoiDan::find()
                    ->where(['!=', 'nhom_doi_tuong', 'Thường'])
                    ->andWhere(['status' => 1])
                    ->orderBy(['updated_at' => SORT_DESC])
                    ->limit(3)->all(),
                'list_key' => 'ho_ten',
                'list_badge' => 'nhom_doi_tuong'
            ],
            
            // Lớp Tuần tra - Giám sát
            'tuanTraGiamSat' => [
                'counts' => [
                    'Camera An ninh' => (int) CameraAnNinh::find()->where(['status' => 1])->count(),
                    'Chốt tuần tra' => (int) ChotTuantre::find()->where(['status' => 1])->count(),
                ],
                'list' => CameraAnNinh::find() // Lấy camera đang offline
                    ->where(['trang_thai' => 'Offline'])
                    ->andWhere(['status' => 1])
                    ->orderBy(['updated_at' => SORT_DESC])
                    ->limit(3)->all(),
                'list_key' => 'ten_diem',
                'list_badge' => 'trang_thai'
            ],
            
            // Lớp Vụ việc
            'vuViec' => [
                'counts' => [
                    'Vụ việc đang xử lý' => (int) VuViec::find()
                        ->where(['!=', 'trang_thai_hien_tai_id', $idDaGiaiQuyet])
                        ->andWhere(['status' => 1])
                        ->count(),
                    'Điểm nhạy cảm' => (int) DiemNhayCam::find()->where(['status' => 1])->count(),
                ],
                'list' => VuViec::find() 
                    ->where(['status' => 1])
                    ->orderBy(['created_at' => SORT_DESC])// Lấy vụ việc mới nhất
                    ->limit(3)->with('linhVuc')
                    ->all(),
                'list_key' => 'tom_tat_noi_dung',
                'list_badge' => 'linhVuc.ten_linh_vuc'
            ],

            // Lớp PCCC
            'pccc' => [
                'counts' => [
                    'Cơ sở nguy cơ cháy' => (int) CosonguycoChayno::find()->where(['status' => 1])->count(),
                    'Nguồn nước CCC' => (int) NguonNuocCcc::find()->where(['status' => 1])->count(),
                    'Trụ nước CCC' => (int) TruNuocCcc::find()->where(['status' => 1])->count(),
                ],
                'list' => CosonguycoChayno::find() // Lấy cơ sở nguy cơ cao
                    ->where(['IN', 'muc_do_nguy_co', ['Cao', 'Rất Cao']])
                    ->andWhere(['status' => 1])
                    ->orderBy(['updated_at' => SORT_DESC])
                    ->limit(3)->all(),
                'list_key' => 'ten_co_so',
                'list_badge' => 'muc_do_nguy_co'
            ],
        ];

        // === 4. DỮ LIỆU BIỂU ĐỒ ===
        // Biểu đồ đường: Xu hướng vụ việc
        $trendData = VuViec::find()
            ->select(['ngay' => new Expression('DATE(created_at)'), 'count' => 'COUNT(*)'])
            ->where(['>=', 'created_at', new Expression('NOW() - INTERVAL \'30 days\'')])
            ->andWhere(['status' => 1])
            ->groupBy(['ngay'])->orderBy('ngay ASC')
            ->asArray()->all();
        $trendChartLabels = [];
        $trendChartValues = [];
        $trendDataMap = ArrayHelper::map($trendData, 'ngay', 'count');
        for ($i = 29; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $trendChartLabels[] = date('d/m', strtotime($date));
            $trendChartValues[] = isset($trendDataMap[$date]) ? (int)$trendDataMap[$date] : 0;
        }
        $trendChartData = ['labels' => $trendChartLabels, 'data' => $trendChartValues];

        // Biểu đồ tròn: Trạng thái xử lý
        $dataByStatus = VuViec::find()
            ->select(['trang_thai_hien_tai_id', 'count' => 'COUNT(*)'])
            ->where(['status' => 1])
            ->groupBy('trang_thai_hien_tai_id')->with('trangThaiHienTai')
            ->asArray()->all();
        $statusChartData = [
            'labels' => ArrayHelper::getColumn($dataByStatus, fn($item) => $item['trangThaiHienTai']['ten_trang_thai'] ?? 'N/A'),
            'data' => array_map('intval', ArrayHelper::getColumn($dataByStatus, 'count')),
        ];

        // === RENDER VIEW ===
        return $this->render('index', [
            // 'tenPhuongXa' => $tenPhuongXa, // Đã bỏ
            'kpis' => $kpis,
            'topCanhBaoDo' => $topCanhBaoDo,
            'topQuaHan' => $topQuaHan,
            'layerData' => $layerData,
            'trendChartData' => $trendChartData,
            'statusChartData' => $statusChartData,
        ]);
    }
}

