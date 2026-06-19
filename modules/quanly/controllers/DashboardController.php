<?php

namespace app\modules\quanly\controllers;

use app\modules\quanly\base\QuanlyBaseController;
use app\modules\quanly\models\CameraAnNinh;
use app\modules\quanly\models\ChotTuantre;
use app\modules\quanly\models\CosokinhdoanhCodk;
use app\modules\quanly\models\CosonguycoChayno;
use app\modules\quanly\models\DiemNhayCam;
use app\modules\quanly\models\DiemTenannxh;
use app\modules\quanly\models\DiemTrongDiem;
use app\modules\quanly\models\KhuvucPhuctapAnNinh;
use app\modules\quanly\models\MuctieuTrongdiem;
use app\modules\quanly\models\NguonNuocCcc;
use app\modules\quanly\models\TruNuocCcc;
use app\modules\quanly\models\VuViec;
use app\modules\quanly\models\NocGia;
use Yii;

/**
 * DashboardController (Bảng Điều hành Nghiệp vụ ANTT v5 - Phân loại màu thông minh)
 */
class DashboardController extends QuanlyBaseController
{
    /**
     * Phân loại chuỗi trạng thái tiếng Việt sang màu tương ứng (Đỏ, Vàng, Xanh)
     * 
     * @param string|null $value Giá trị chuỗi trạng thái
     * @param string $defaultColor Màu mặc định khi giá trị rỗng/null
     * @return string 'do' | 'vang' | 'xanh'
     */
    private static function classifyStatus($value, $defaultColor = 'xanh')
    {
        if ($value === null || trim($value) === '') {
            return $defaultColor;
        }

        $valLower = mb_strtolower(trim($value), 'UTF-8');

        // 1. NHÓM ĐỎ: Các trạng thái tiêu cực nghiêm trọng, sự cố, mất kết nối, đình chỉ hoạt động
        $redKeywords = [
            'hỏng', 'hư hỏng', 'không hoạt động', 'offline', 'mất tín hiệu', 
            'đình chỉ', 'đóng cửa', 'nguy cơ cao', 'rất cao', 'nghiêm trọng', 
            'khẩn cấp', 'không đạt', 'sự cố', 'nguy hiểm', 'đỏ', 
            'hư hại', 'cao'
            
        ];

        // 2. NHÓM VÀNG: Trạng thái trung bình, cần theo dõi, tạm ngưng, thiếu hụt, nhắc nhở
        $yellowKeywords = [
            'trung bình', 'tạm ngưng', 'chờ xử lý', 'đang xử lý', 'cảnh báo', 
            'cần sửa chữa', 'cần bảo trì', 'theo dõi', 'hạn chế',
            'lưu động', 'yếu', 'thấp', 'nhắc nhở',
            'thiếu', 'không ổn định'
        ];

        // 3. NHÓM XANH: Trạng thái tốt, ổn định, hoạt động bình thường, an toàn
        $greenKeywords = [
            'tốt', 'hoạt động tốt', 'đang hoạt động', 'hoạt động', 'online', 
            'ổn định', 'cố định', 'đã xử lý', 'bình thường', 'đạt', 'xanh',
            'an toàn', 'thấp'
        ];

        // Duyệt tìm từ khóa Đỏ
        foreach ($redKeywords as $kw) {
            if (mb_strpos($valLower, $kw, 0, 'UTF-8') !== false) {
                return 'do';
            }
        }

        // Duyệt tìm từ khóa Vàng
        foreach ($yellowKeywords as $kw) {
            if (mb_strpos($valLower, $kw, 0, 'UTF-8') !== false) {
                return 'vang';
            }
        }

        // Duyệt tìm từ khóa Xanh
        foreach ($greenKeywords as $kw) {
            if (mb_strpos($valLower, $kw, 0, 'UTF-8') !== false) {
                return 'xanh';
            }
        }

        // Nếu chuỗi không rỗng nhưng không khớp bất kỳ nhóm nào, mặc định phân vào Vàng (cần theo dõi)
        return 'vang';
    }

    /**
     * Đếm và phân loại trạng thái Đỏ/Vàng/Xanh cho một Model dựa trên cột chỉ định
     */
    private static function aggregateLayerStats($modelClass, $statusField, $defaultColor = 'xanh')
    {
        $records = $modelClass::find()->where(['status' => 1])->select([$statusField])->asArray()->all();
        
        $do = 0;
        $vang = 0;
        $xanh = 0;

        foreach ($records as $r) {
            $val = isset($r[$statusField]) ? $r[$statusField] : null;
            $color = self::classifyStatus($val, $defaultColor);
            if ($color === 'do') {
                $do++;
            } elseif ($color === 'vang') {
                $vang++;
            } else {
                $xanh++;
            }
        }

        return ['do' => $do, 'vang' => $vang, 'xanh' => $xanh];
    }

    public function actionIndex()
    {
        // 1. Mục tiêu trọng điểm
        $mtTrongdiem = self::aggregateLayerStats(MuctieuTrongdiem::class, 'trang_thai_an_ninh', 'xanh');

        // 2. Khu vực phức tạp AN
        $kvPhuctap = self::aggregateLayerStats(KhuvucPhuctapAnNinh::class, 'muc_do_phuctap', 'xanh');

        // 3. Cơ sở nguy cơ cháy nổ
        $csChayno = self::aggregateLayerStats(CosonguycoChayno::class, 'muc_do_nguy_co', 'xanh');

        // 4. Trụ nước PCCC
        $truNuoc = self::aggregateLayerStats(TruNuocCcc::class, 'tinh_trang', 'xanh');

        // 5. Nguồn nước PCCC
        $nguonNuoc = self::aggregateLayerStats(NguonNuocCcc::class, 'tinh_trang', 'xanh');

        // 6. Cơ sở kinh doanh có điều kiện
        $csKinhdoanh = self::aggregateLayerStats(CosokinhdoanhCodk::class, 'trang_thai_hoat_dong', 'xanh');

        // 7. Điểm tệ nạn xã hội (xem xét cả mức độ nguy cơ và tình trạng xử lý)
        $diemTenanRecords = DiemTenannxh::find()->where(['status' => 1])->select(['muc_do_nguy_co', 'tinh_trang_xu_ly'])->asArray()->all();
        $diemTenanDo = 0; $diemTenanVang = 0; $diemTenanXanh = 0;
        foreach ($diemTenanRecords as $r) {
            $mucDo = isset($r['muc_do_nguy_co']) ? $r['muc_do_nguy_co'] : null;
            $tinhTrang = isset($r['tinh_trang_xu_ly']) ? $r['tinh_trang_xu_ly'] : null;
            
            $c1 = self::classifyStatus($mucDo, 'xanh');
            $c2 = self::classifyStatus($tinhTrang, 'xanh');
            
            if ($c1 === 'do' || $c2 === 'do') {
                $diemTenanDo++;
            } elseif ($c1 === 'vang' || $c2 === 'vang') {
                $diemTenanVang++;
            } else {
                $diemTenanXanh++;
            }
        }
        $diemTenan = ['do' => $diemTenanDo, 'vang' => $diemTenanVang, 'xanh' => $diemTenanXanh];

        // 8. Camera an ninh
        $camera = self::aggregateLayerStats(CameraAnNinh::class, 'trang_thai', 'xanh');

        // 9. Chốt tuần tra
        $chotTuantra = self::aggregateLayerStats(ChotTuantre::class, 'loai_chot', 'xanh');

        // 10. Vụ việc
        $vuViec = self::aggregateLayerStats(VuViec::class, 'muc_do_canh_bao', 'xanh');

        // 11. Điểm nhạy cảm (Tất cả mặc định cảnh báo Vàng)
        $nhayCamCount = (int) DiemNhayCam::find()->where(['status' => 1])->count();
        $nhayCam = ['do' => 0, 'vang' => $nhayCamCount, 'xanh' => 0];

        // 12. Điểm trọng điểm (Chứa chữ 'ma túy'/'ma tuy' -> Đỏ, còn lại Vàng)
        $trongDiemRecords = DiemTrongDiem::find()->where(['status' => 1])->select(['tenloaihinh'])->asArray()->all();
        $trongDiemDo = 0; $trongDiemVang = 0; $trongDiemXanh = 0;
        foreach ($trongDiemRecords as $r) {
            $val = isset($r['tenloaihinh']) ? $r['tenloaihinh'] : '';
            $valLower = mb_strtolower($val, 'UTF-8');
            if (mb_strpos($valLower, 'ma túy') !== false || mb_strpos($valLower, 'ma tuy') !== false) {
                $trongDiemDo++;
            } else {
                $trongDiemVang++;
            }
        }
        $trongDiem = ['do' => $trongDiemDo, 'vang' => $trongDiemVang, 'xanh' => $trongDiemXanh];

        // 13. Nóc gia (Tất cả mặc định Xanh)
        $nocGiaCount = (int) NocGia::find()->where(['status' => 1])->count();
        $nocGia = ['do' => 0, 'vang' => 0, 'xanh' => $nocGiaCount];

        // --- TỔNG HỢP DỮ LIỆU BIỂU ĐỒ ---
        $chartData = [
            'labels' => [
                'Mục tiêu trọng điểm', 'Khu vực phức tạp AN', 'Cơ sở cháy nổ', 'Trụ nước PCCC', 'Nguồn nước PCCC',
                'Cơ sở KD có ĐK', 'Điểm tệ nạn XH', 'Camera an ninh', 'Chốt tuần tra', 'Vụ việc',
                'Điểm nhạy cảm', 'Điểm trọng điểm', 'Nóc gia'
            ],
            'do' => [
                $mtTrongdiem['do'], $kvPhuctap['do'], $csChayno['do'], $truNuoc['do'], $nguonNuoc['do'],
                $csKinhdoanh['do'], $diemTenan['do'], $camera['do'], $chotTuantra['do'], $vuViec['do'],
                $nhayCam['do'], $trongDiem['do'], $nocGia['do']
            ],
            'vang' => [
                $mtTrongdiem['vang'], $kvPhuctap['vang'], $csChayno['vang'], $truNuoc['vang'], $nguonNuoc['vang'],
                $csKinhdoanh['vang'], $diemTenan['vang'], $camera['vang'], $chotTuantra['vang'], $vuViec['vang'],
                $nhayCam['vang'], $trongDiem['vang'], $nocGia['vang']
            ],
            'xanh' => [
                $mtTrongdiem['xanh'], $kvPhuctap['xanh'], $csChayno['xanh'], $truNuoc['xanh'], $nguonNuoc['xanh'],
                $csKinhdoanh['xanh'], $diemTenan['xanh'], $camera['xanh'], $chotTuantra['xanh'], $vuViec['xanh'],
                $nhayCam['xanh'], $trongDiem['xanh'], $nocGia['xanh']
            ],
        ];

        $summaryChartData = [
            'labels' => ['Đỏ (Nguy cơ / Sự cố / Đình chỉ)', 'Vàng (Trung bình / Cảnh báo)', 'Xanh (Ổn định / Đang hoạt động)'],
            'data' => [
                array_sum($chartData['do']),
                array_sum($chartData['vang']),
                array_sum($chartData['xanh']),
            ],
        ];

        $layerData = [
            'muctieu_trongdiem' => [
                'title' => 'Mục tiêu trọng điểm',
                'chart' => $mtTrongdiem,
            ],
            'khuvuc_phuctap_an_ninh' => [
                'title' => 'Khu vực phức tạp AN',
                'chart' => $kvPhuctap,
            ],
            'cosonguyco_chayno' => [
                'title' => 'Cơ sở nguy cơ cháy nổ',
                'chart' => $csChayno,
            ],
            'tru_nuoc_ccc' => [
                'title' => 'Trụ nước PCCC',
                'chart' => $truNuoc,
            ],
            'nguon_nuoc_ccc' => [
                'title' => 'Nguồn nước PCCC',
                'chart' => $nguonNuoc,
            ],
            'cosokinhdoanh_codk' => [
                'title' => 'Cơ sở KD có ĐK',
                'chart' => $csKinhdoanh,
            ],
            'diem_tenannxh' => [
                'title' => 'Điểm tệ nạn xã hội',
                'chart' => $diemTenan,
            ],
            'camera_an_ninh' => [
                'title' => 'Camera an ninh',
                'chart' => $camera,
            ],
            'chot_tuantre' => [
                'title' => 'Chốt tuần tra',
                'chart' => $chotTuantra,
            ],
            'vu_viec' => [
                'title' => 'Vụ việc',
                'chart' => $vuViec,
            ],
            'diem_nhay_cam' => [
                'title' => 'Điểm nhạy cảm',
                'chart' => $nhayCam,
            ],
            'diem_trong_diem' => [
                'title' => 'Điểm trọng điểm',
                'chart' => $trongDiem,
            ],
            'noc_gia' => [
                'title' => 'Nóc gia',
                'chart' => $nocGia,
            ],
        ];

        return $this->render('index', [
            'chartData' => $chartData,
            'summaryChartData' => $summaryChartData,
            'layerData' => $layerData,
        ]);
    }
}
