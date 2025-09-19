<?php

namespace app\modules\quanly\controllers;

use app\modules\quanly\base\QuanlyBaseController;
use app\modules\quanly\models\LinhVuc;
use app\modules\quanly\models\Phuongxa; // MỚI: Thêm model Phuongxa
use app\modules\quanly\models\TrangThaiXuLy;
use app\modules\quanly\models\VuViec;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Response;

class DashboardController extends QuanlyBaseController
{
    public function actionIndex()
    {
        if((Yii::$app->user->identity->is_nguoidan || Yii::$app->user->identity->canbo_id != null)){
            $this->redirect(['vu-viec/index']);
        }

        // if(Yii::$app->user->identity->captaikhoan != null && Yii::$app->user->identity->captaikhoan == 1){
        //     $this->redirect(['map/index']);
        // }

        // Lấy dữ liệu ban đầu cho lần tải trang đầu tiên
        $initialData = $this->getDashboardData();

        // MỚI: Lấy danh sách phường xã cho bộ lọc
        $phuongXaList = ArrayHelper::map(Phuongxa::find()->orderBy('ten_dvhc')->asArray()->all(), 'ma_dvhc', 'ten_dvhc');

        if(Yii::$app->user->identity->phuongxa != null){
            $phuongXaList = ArrayHelper::map(Phuongxa::find()->where(['ma_dvhc' => Yii::$app->user->identity->phuongxa])->orderBy('ten_dvhc')->asArray()->all(), 'ma_dvhc', 'ten_dvhc');
        }

        return $this->render('index', [
            'initialDataJson' => Json::encode($initialData),
            'phuongXaList' => $phuongXaList, // MỚI: Truyền danh sách sang view
        ]);
    }

    /**
     * Action này xử lý các yêu cầu AJAX để làm mới dữ liệu dashboard khi người dùng thay đổi bộ lọc.
     */
    public function actionFilterData()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $dateRange = $request->get('date_range');
        $maPhuongXa = $request->get('ma_phuongxa'); // MỚI: Nhận tham số lọc phường xã

        if(Yii::$app->user->identity->phuongxa != null){
            $maPhuongXa = Yii::$app->user->identity->phuongxa;
        }else{
            $maPhuongXa = $request->get('ma_phuongxa');
        }

        return $this->getDashboardData($dateRange, $maPhuongXa); // CẬP NHẬT: Truyền tham số mới
    }

    /**
     * Hàm trung tâm để lấy tất cả dữ liệu cho dashboard.
     * @param string|null $dateRange Khoảng thời gian lọc (ví dụ: "2025-08-01 - 2025-08-27")
     * @param string|null $maPhuongXa Mã ĐVHC của phường xã cần lọc
     * @return array
     */
    private function getDashboardData($dateRange = null, $maPhuongXa = null) // CẬP NHẬT: Thêm tham số $maPhuongXa
    {
        $query = VuViec::find();

        // Áp dụng bộ lọc thời gian nếu có
        if ($dateRange) {
            $dates = explode(' - ', $dateRange);
            if (count($dates) == 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dates[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dates[1]));
                $query->andWhere(['between', 'ngay_tiep_nhan', $startDate, $endDate]);
            }
        }
        
        // MỚI: Áp dụng bộ lọc phường xã cho toàn bộ query
        if ($maPhuongXa) {
            $query->andWhere(['ma_dvhc_phuongxa' => $maPhuongXa]);
        }

        if(Yii::$app->user->identity->phuongxa != null){
            $query->andWhere(['ma_dvhc_phuongxa' => Yii::$app->user->identity->phuongxa]);
        }


        // 1. Dữ liệu cho các chỉ số KPI
        $kpiData = $this->getKpiData($query);

        // 2. Dữ liệu cho các biểu đồ
        // CẬP NHẬT: Truyền cả 2 tham số lọc vào hàm getChartData
        $chartData = $this->getChartData($query, $dateRange, $maPhuongXa);

        // 3. Dữ liệu cho các bảng
        $tableData = $this->getTableData($query);

        return [
            'kpi' => $kpiData,
            'charts' => $chartData,
            'tables' => $tableData,
        ];
    }

    /**
     * Lấy dữ liệu cho các thẻ KPI.
     * @param \yii\db\ActiveQuery $query
     * @return array
     */
    private function getKpiData($query)
    {
        $total = (clone $query)->count();
        $redAlerts = (clone $query)->andWhere(['muc_do_canh_bao' => VuViec::CANH_BAO_DO])->count();
        $overdue = (clone $query)
            ->joinWith('trangThaiHienTai ts')
            ->andWhere(['<', 'han_xu_ly', new Expression('NOW()')])
            ->andWhere(['<>', 'ts.ten_trang_thai', 'Đã giải quyết'])
            ->count();
        $resolved = (clone $query)
            ->joinWith('trangThaiHienTai ts')
            ->andWhere(['ts.ten_trang_thai' => 'Đã giải quyết'])
            ->count();
            // dd($overdue);

        return compact('total', 'redAlerts', 'overdue', 'resolved');
    }

    /**
     * Lấy dữ liệu cho các biểu đồ.
     * @param \yii\db\ActiveQuery $query
     * @param string|null $dateRange
     * @param string|null $maPhuongXa
     * @return array
     */
    private function getChartData($query, $dateRange, $maPhuongXa) // CẬP NHẬT: Nhận tham số lọc
    {
        // Biểu đồ xu hướng theo ngày
        $trendQuery = (clone $query); // Đã được lọc theo phường xã từ hàm gọi
        if (!$dateRange) { // Nếu không có bộ lọc ngày tháng, mặc định lấy 30 ngày gần nhất
            $trendQuery->andWhere(['>=', 'ngay_tiep_nhan', new Expression("NOW() - INTERVAL '30 day'")]);
        }
        $trend = $trendQuery
            ->select(['date' => 'DATE(ngay_tiep_nhan)', 'count' => 'COUNT(*)'])
            ->groupBy('date')
            ->orderBy('date ASC')
            ->asArray()
            ->all();

        // Biểu đồ cơ cấu theo lĩnh vực
        $byDomain = (clone $query)
            ->select(['lv.ten_linh_vuc', 'total' => 'COUNT(vu_viec.id)'])
            ->joinWith('linhVuc lv')
            ->groupBy('lv.ten_linh_vuc')
            ->orderBy(['total' => SORT_DESC])
            ->limit(6) // Giới hạn 6 lĩnh vực cao nhất cho biểu đồ gọn
            ->asArray()
            ->all();

        // Biểu đồ cơ cấu theo mức độ cảnh báo
        $byAlertLevel = (clone $query)
            ->select(['muc_do_canh_bao', 'total' => 'COUNT(*)'])
            ->andWhere(['is not', 'muc_do_canh_bao', null])
            ->groupBy('muc_do_canh_bao')
            ->asArray()
            ->all();

        return compact('trend', 'byDomain', 'byAlertLevel');
    }

    /**
     * Lấy dữ liệu cho các bảng.
     * @param \yii\db\ActiveQuery $query
     * @return array
     */
    private function getTableData($query)
    {
        // Bảng: Các vụ việc "nóng" gần đây
        $hotIncidents = (clone $query)
            ->andWhere(['in', 'muc_do_canh_bao', [VuViec::CANH_BAO_DO, VuViec::CANH_BAO_VANG]])
            ->with(['linhVuc', 'phuongXa'])
            ->orderBy(['ngay_tiep_nhan' => SORT_DESC])
            ->limit(5)
            ->asArray()
            ->all();

        // Bảng: Các vụ việc sắp đến hạn
        $approachingDeadline = (clone $query)
            ->joinWith('trangThaiHienTai ts')
            ->andWhere(['between', 'han_xu_ly', new Expression('NOW()'), new Expression("NOW() + INTERVAL '5 day'")])
            ->andWhere(['<>', 'ts.ten_trang_thai', 'Đã giải quyết'])
            ->with(['linhVuc', 'phuongXa'])
            ->orderBy(['han_xu_ly' => SORT_ASC])
            ->limit(5)
            ->asArray()
            ->all();
            
        return compact('hotIncidents', 'approachingDeadline');
    }
}