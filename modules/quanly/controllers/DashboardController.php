<?php

namespace app\modules\quanly\controllers;

use app\modules\quanly\base\QuanlyBaseController;
use app\modules\quanly\models\DiemNhayCam;
use app\modules\quanly\models\DiemTrongDiem;
use app\modules\quanly\models\HoGiaDinh;
use app\modules\quanly\models\NguoiDan;
use app\modules\quanly\models\NocGia;
use app\modules\quanly\models\Phuongxa;
use app\modules\quanly\models\TrangThaiXuLy;
use app\modules\quanly\models\VuViec;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class DashboardController extends QuanlyBaseController
{
    /**
     * Hiển thị trang dashboard chính với các số liệu thống kê và biểu đồ.
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        // === 1. LẤY DỮ LIỆU CHO CÁC THẺ KPI ===
        $totalVuViec = VuViec::find()->count();
        $vuViecCanhBaoDo = VuViec::find()->where(['muc_do_canh_bao' => VuViec::CANH_BAO_DO])->count();
        $totalHoGiaDinh = HoGiaDinh::find()->count();
        $totalNocGia = NocGia::find()->count();
        $totalDiemNhayCam = DiemNhayCam::find()->count();
        $totalDiemTrongDiem = DiemTrongDiem::find()->count();
        $totalNguoiDan = NguoiDan::find()->count();
        $totalPhuongXa = Phuongxa::find()->count();
        
        // Lấy ID của trạng thái 'Đã giải quyết' để loại trừ khỏi các vụ việc quá hạn
        $trangThaiDaGiaiQuyet = TrangThaiXuLy::findOne(['ten_trang_thai' => 'Đã giải quyết']);
        $idDaGiaiQuyet = $trangThaiDaGiaiQuyet ? $trangThaiDaGiaiQuyet->id : -1;

        // === 2. LẤY DỮ LIỆU CHO CÁC BIỂU ĐỒ ===

        // Biểu đồ tròn: Thống kê theo trạng thái
        $dataByStatus = VuViec::find()
            ->select(['trang_thai_hien_tai_id', 'count' => 'COUNT(*)'])
            ->groupBy('trang_thai_hien_tai_id')
            ->with('trangThaiHienTai')
            ->asArray()
            ->all();

        $statusChartData = [
            'labels' => ArrayHelper::getColumn($dataByStatus, function ($item) {
                return $item['trangThaiHienTai']['ten_trang_thai'] ?? 'Chưa xác định';
            }),
            'data' => array_map('intval', ArrayHelper::getColumn($dataByStatus, 'count')),
        ];

        // Biểu đồ cột: Thống kê theo lĩnh vực
        $dataByLinhVuc = VuViec::find()
            ->select(['linh_vuc_id', 'count' => 'COUNT(*)'])
            ->groupBy('linh_vuc_id')
            ->with('linhVuc')
            ->asArray()
            ->all();

        $linhVucChartData = [
            'labels' => ArrayHelper::getColumn($dataByLinhVuc, function ($item) {
                return $item['linhVuc']['ten_linh_vuc'] ?? 'Chưa xác định';
            }),
            'data' => array_map('intval', ArrayHelper::getColumn($dataByLinhVuc, 'count')),
        ];
        
        // Biểu đồ đường: Xu hướng vụ việc trong 30 ngày qua
        $trendData = VuViec::find()
            ->select(['ngay' => new Expression('DATE(created_at)'), 'count' => 'COUNT(*)'])
            ->where(['>=', 'created_at', new Expression('NOW() - INTERVAL \'30 days\'')])
            ->groupBy(['ngay'])
            ->orderBy('ngay ASC')
            ->asArray()
            ->all();

        // Chuẩn bị dữ liệu đầy đủ 30 ngày để biểu đồ không bị đứt gãy
        $trendChartLabels = [];
        $trendChartValues = [];
        $trendDataMap = ArrayHelper::map($trendData, 'ngay', 'count');
        for ($i = 29; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $trendChartLabels[] = date('d/m', strtotime($date));
            $trendChartValues[] = isset($trendDataMap[$date]) ? (int)$trendDataMap[$date] : 0;
        }
        $trendChartData = [
            'labels' => $trendChartLabels,
            'data' => $trendChartValues,
        ];

        // === 3. LẤY DỮ LIỆU CHO CÁC DANH SÁCH ===

        // Danh sách 5 vụ việc cảnh báo đỏ mới nhất
        $topCanhBaoDo = VuViec::find()
            ->where(['muc_do_canh_bao' => VuViec::CANH_BAO_DO])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(5)
            ->with('linhVuc', 'phuongXa')
            ->all();

        // Danh sách 5 vụ việc quá hạn cấp bách nhất
        $topQuaHan = VuViec::find()
            ->where(['<', 'han_xu_ly', date('Y-m-d H:i:s')])
            ->andWhere(['!=', 'trang_thai_hien_tai_id', $idDaGiaiQuyet])
            ->orderBy(['han_xu_ly' => SORT_ASC])
            ->limit(5)
            ->with('linhVuc', 'phuongXa', 'trangThaiHienTai')
            ->all();

        return $this->render('index', [
            'kpis' => [
                'totalVuViec' => $totalVuViec,
                'highRisk' => $vuViecCanhBaoDo,
                'totalHoGiaDinh' => $totalHoGiaDinh,
                'totalNocGia' => $totalNocGia,
                'totalDiemNhayCam' => $totalDiemNhayCam,
                'totalDiemTrongDiem' => $totalDiemTrongDiem,
                'totalNguoiDan' => $totalNguoiDan,
                'totalPhuongXa' => $totalPhuongXa,
            ],
            'statusChartData' => $statusChartData,
            'linhVucChartData' => $linhVucChartData,
            'trendChartData' => $trendChartData,
            'topCanhBaoDo' => $topCanhBaoDo,
            'topQuaHan' => $topQuaHan,
        ]);
    }
}

