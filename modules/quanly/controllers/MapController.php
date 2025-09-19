<?php

namespace app\modules\quanly\controllers;

use app\modules\quanly\models\Kp;
use app\modules\quanly\models\LinhVuc;
use app\modules\quanly\models\Phuongxa;
use app\modules\quanly\models\VuViec;
use Yii;
use yii\db\Expression;
use yii\helpers\Json;
use yii\web\Response;

class MapController extends \app\modules\quanly\base\QuanlyBaseController
{
    public $layout = '@app/views/layouts/map/main';

    public function actionIndex()
    {
        $allVuViec = $this->getVuViecDataQuery()->asArray()->all();

        $linhVucList = LinhVuc::find()->select(['id', 'ten_linh_vuc'])->asArray()->all();
        $phuongXaList = Phuongxa::find()->select(['ma_dvhc', 'ten_dvhc'])->orderBy('ten_dvhc')->asArray()->all();
        $kpiData = $this->getKpiData();
        $chartData = $this->getChartData();

        return $this->render('map', [
            'allVuViecJson' => Json::encode($allVuViec),
            'linhVucList' => $linhVucList,
            'phuongXaList' => $phuongXaList,
            'kpiData' => $kpiData,
            'chartDataJson' => Json::encode($chartData),
        ]);
    }

    public function actionFilterData()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $linhVucId = $request->get('linh_vuc_id');
        $maDvhc = $request->get('ma_dvhc');
        $dateRange = $request->get('date_range');

        $query = $this->getVuViecDataQuery();

        if ($linhVucId) $query->andWhere(['linh_vuc_id' => $linhVucId]);
        if ($maDvhc) $query->andWhere(['ma_dvhc_phuongxa' => $maDvhc]);
        if ($dateRange) {
            $dates = explode(' - ', $dateRange);
            if (count($dates) == 2) {
                $startDate = date('Y-m-d', strtotime($dates[0]));
                $endDate = date('Y-m-d', strtotime($dates[1]));
                $query->andWhere(['between', 'DATE(ngay_tiep_nhan)', $startDate, $endDate]);
            }
        }
        return $query->asArray()->all();
    }
    
    private function getVuViecDataQuery()
    {
        return VuViec::find()
            ->select([
                'vu_viec.id', 'ma_vu_viec', 'tom_tat_noi_dung', 'ngay_tiep_nhan',
                'diem_rui_ro', 'muc_do_canh_bao',
                'ST_AsGeoJSON(vi_tri_su_viec) as geojson',
                'linh_vuc_id', 'trang_thai_hien_tai_id', 'nguoi_dan_id', 'can_bo_tiep_nhan_id'
            ])
            ->with([
                'linhVuc',
                'trangThaiHienTai',
                'nguoiDan',
                'canBoTiepNhan',
                'lichSuXuLies' => function ($query) {
                    $query->with(['canBoThucHien', 'trangThai'])->orderBy(['ngay_thuc_hien' => SORT_ASC]);
                }
            ]);
    }

    public function actionGetPhuongxaGeojson() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $phuongxas = Phuongxa::find()
            ->select(['*', new Expression('ST_AsGeoJSON(geom) as geojson')])
            ->asArray()->all();
        
        $features = [];
        foreach($phuongxas as $phuongxa) {
            $properties = [
                'ten_dvhc' => $phuongxa['ten_dvhc'], 'tinh_thanh' => $phuongxa['tinh_thanh'],
                'quanhuyen_cu' => $phuongxa['quanhuyen_cu'], 'tinhthanh_cu' => $phuongxa['tinhthanh_cu'],
                'sapxeptu' => $phuongxa['sapxeptu'], 'dan_so' => $phuongxa['dan_so'],
                'dien_tich' => $phuongxa['dien_tich'], 'tsdvhc_cap' => $phuongxa['tsdvhc_cap'],
                'so_xa' => $phuongxa['so_xa'], 'so_phuong' => $phuongxa['so_phuong'],
            ];
            $features[] = ['type' => 'Feature', 'properties' => $properties, 'geometry' => Json::decode($phuongxa['geojson'])];
        }
        return ['type' => 'FeatureCollection', 'features' => $features];
    }

    public function actionGetKpGeojson() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $kps = Kp::find()
            ->alias('kp')
            ->select([
                'kp.*', 'px.ten_dvhc as phuongxa_ten_dvhc', 'px.tinhthanh_cu as phuongxa_tinhthanh_cu',
                'px.bi_thu as phuongxa_bi_thu', 'px.ho_ten_ct as phuongxa_ho_ten_ct',
                'px.ten_phongx as phuongxa_ten_phongx', 'px.sdt_ctubnd as phuongxa_sdt_ctubnd',
                'px.sdt_bithu as phuongxa_sdt_bithu', new Expression('ST_AsGeoJSON(kp.geom) as geojson')
            ])
            ->leftJoin('phuongxa px', 'kp.mv_dvhc = px.ma_dvhc')
            ->asArray()->all();
        
        $features = [];
        foreach($kps as $kp) {
            $properties = [
                'id' => $kp['OBJECTID'], 'ten_kp' => $kp['TenKhuPho'], 'ten_phuong' => $kp['TenPhuong'],
                'phuongxa_ten_dvhc' => $kp['phuongxa_ten_dvhc'], 'phuongxa_tinhthanh_cu' => $kp['phuongxa_tinhthanh_cu'],
                'phuongxa_bi_thu' => $kp['phuongxa_bi_thu'], 'phuongxa_ho_ten_ct' => $kp['phuongxa_ho_ten_ct'],
                'phuongxa_ten_phongx' => $kp['phuongxa_ten_phongx'], 'phuongxa_sdt_ctubnd' => $kp['phuongxa_sdt_ctubnd'],
                'phuongxa_sdt_bithu' => $kp['phuongxa_sdt_bithu'],
            ];
            $features[] = ['type' => 'Feature', 'properties' => $properties, 'geometry' => Json::decode($kp['geojson'])];
        }
        return ['type' => 'FeatureCollection', 'features' => $features];
    }

    public function actionGetKpRiskData()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;

        $vuViecQuery = VuViec::find()->where(['is not', 'objectid_khupho', null]);
        $linhVucId = $request->get('linh_vuc_id');
        $maDvhc = $request->get('ma_dvhc');
        $dateRange = $request->get('date_range');

        if ($linhVucId) $vuViecQuery->andWhere(['linh_vuc_id' => $linhVucId]);
        if ($maDvhc) $vuViecQuery->andWhere(['ma_dvhc_phuongxa' => $maDvhc]);
        if ($dateRange) {
            $dates = explode(' - ', $dateRange);
            if (count($dates) == 2) {
                $startDate = date('Y-m-d', strtotime($dates[0]));
                $endDate = date('Y-m-d', strtotime($dates[1]));
                $vuViecQuery->andWhere(['between', 'DATE(ngay_tiep_nhan)', $startDate, $endDate]);
            }
        }

        $riskScores = (new \yii\db\Query())
            ->select(['objectid_khupho', 'total_risk' => 'SUM(diem_rui_ro)'])
            ->from(['filtered_vv' => $vuViecQuery])
            ->groupBy('objectid_khupho')
            ->orderBy(['total_risk' => SORT_ASC])
            ->all();

        if (empty($riskScores)) return [];

        $count = count($riskScores);
        $quantiles = [
            'q1' => $riskScores[floor($count * 0.4)]['total_risk'], 'q2' => $riskScores[floor($count * 0.7)]['total_risk'],
            'q3' => $riskScores[floor($count * 0.9)]['total_risk'],
        ];

        $riskData = [];
        foreach ($riskScores as $score) {
            $totalRisk = $score['total_risk'];
            $color = '#2ecc71'; $level = 'Bình thường';
            if ($totalRisk > $quantiles['q3']) { $color = '#e74c3c'; $level = 'Điểm nóng'; } 
            elseif ($totalRisk > $quantiles['q2']) { $color = '#e67e22'; $level = 'Nguy cơ cao'; } 
            elseif ($totalRisk > $quantiles['q1']) { $color = '#f1c40f'; $level = 'Cần chú ý'; }
            $riskData[$score['objectid_khupho']] = ['score' => round($totalRisk), 'color' => $color, 'level' => $level];
        }
        return $riskData;
    }

    // ==================================================================
    // CẬP NHẬT: ACTION TÍNH TOÁN GETIS-ORD GI* BẰNG SQL/POSTGIS
    // ==================================================================
    public function actionGetHotspotData()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $cache = Yii::$app->cache;
        $db = Yii::$app->db;

        $filters = $request->get();
        unset($filters['r']);
        $cacheKey = 'hotspot-analysis-optimal-v2-' . md5(http_build_query($filters)); // Đổi cache key

        $hotspotResults = $cache->get($cacheKey);

        if ($hotspotResults === false) {
            $vuViecSubQuery = (new \yii\db\Query())
                ->select('id')
                ->from('vu_viec')
                ->where(['is not', 'objectid_khupho', null]);

            if ($request->get('linh_vuc_id')) $vuViecSubQuery->andWhere(['linh_vuc_id' => $request->get('linh_vuc_id')]);
            if ($request->get('ma_dvhc')) $vuViecSubQuery->andWhere(['ma_dvhc_phuongxa' => $request->get('ma_dvhc')]);
            if ($request->get('date_range')) {
                $dates = explode(' - ', $request->get('date_range'));
                if (count($dates) == 2) {
                    $vuViecSubQuery->andWhere(['between', 'DATE(ngay_tiep_nhan)', date('Y-m-d', strtotime($dates[0])), date('Y-m-d', strtotime($dates[1]))]);
                }
            }
            
            $sql = <<<SQL
            WITH 
            -- Bước 1: Tính "Chỉ số nóng" bằng cách lấy điểm cơ bản nhân với hệ số "động" theo thứ tự ưu tiên
            vu_viec_hotness AS (
                SELECT 
                    vv.objectid_khupho,
                    (
                        COALESCE(vv.diem_rui_ro, 0) -- Lấy điểm cơ bản
                        * -- Nhân với hệ số theo trạng thái
                        CASE 
                            -- Ghi chú: Thứ tự của các mệnh đề WHEN là rất quan trọng
                            WHEN tt.ten_trang_thai = 'Đã giải quyết' THEN 0.1
                            WHEN vv.is_lap_lai = true THEN 3.0 -- Ưu tiên 1: Lặp lại
                            WHEN tt.ten_trang_thai = 'Quá hạn' THEN 2.5 -- Ưu tiên 2: Trạng thái Quá hạn
                            WHEN vv.han_xu_ly IS NOT NULL AND vv.han_xu_ly < NOW() THEN 2.0 -- Ưu tiên 3: Quá hạn tính động
                            ELSE 1.0 -- Mới tiếp nhận, Đang xử lý
                        END
                    ) as hotness_score
                FROM 
                    vu_viec vv
                LEFT JOIN 
                    trang_thai_xu_ly tt ON vv.trang_thai_hien_tai_id = tt.id
                WHERE 
                    vv.id IN ({$vuViecSubQuery->createCommand()->rawSql})
            ),
            -- Bước 2: Tổng hợp "Chỉ số Nóng" cho mỗi khu phố
            kp_scores_filtered AS (
                SELECT objectid_khupho, SUM(hotness_score) as risk_score
                FROM vu_viec_hotness
                GROUP BY objectid_khupho
            ),
            -- Các bước còn lại hoàn toàn giữ nguyên
            kp_scores AS (
                SELECT k.objectid, COALESCE(f.risk_score, 0) as risk_score
                FROM kp k LEFT JOIN kp_scores_filtered f ON k.objectid = f.objectid_khupho
            ),
            global_stats AS (
                SELECT AVG(risk_score) as mean, STDDEV_POP(risk_score) as std_dev, COUNT(*) as n FROM kp_scores
            ),
            local_sums AS (
                SELECT a.objectid, SUM(b_scores.risk_score) as local_sum, COUNT(b.objectid) as neighbor_count
                FROM kp a
                INNER JOIN kp b ON ST_DWithin(a.geom, b.geom, 0.0001)
                INNER JOIN kp_scores b_scores ON b.objectid = b_scores.objectid
                GROUP BY a.objectid
            )
            SELECT ls.objectid,
                (ls.local_sum - (gs.mean * ls.neighbor_count)) / 
                (gs.std_dev * SQRT((gs.n * ls.neighbor_count - POWER(ls.neighbor_count, 2)) / (gs.n - 1))) as z_score
            FROM local_sums ls, global_stats gs
            WHERE gs.std_dev > 0 AND (gs.n - 1) > 0
            SQL;

            $params = $vuViecSubQuery->params;
            $hotspotResults = [];

            try {
                $results = $db->createCommand($sql, $params)->queryAll();
                foreach ($results as $row) {
                    $zScore = (float)$row['z_score'];
                    $result = ['z_score' => round($zScore, 2), 'type' => 'Not Significant', 'color' => '#95a5a6'];
                    if ($zScore > 2.58) { $result['type'] = 'Hot Spot (99% confidence)'; $result['color'] = '#d63031'; }
                    else if ($zScore > 1.96) { $result['type'] = 'Hot Spot (95% confidence)'; $result['color'] = '#e67e22'; }
                    else if ($zScore > 1.65) { $result['type'] = 'Hot Spot (90% confidence)'; $result['color'] = '#f1c40f'; }
                    else if ($zScore < -2.58) { $result['type'] = 'Cold Spot (99% confidence)'; $result['color'] = '#0984e3'; }
                    else if ($zScore < -1.96) { $result['type'] = 'Cold Spot (95% confidence)'; $result['color'] = '#74b9ff'; }
                    $hotspotResults[$row['objectid']] = $result;
                }
            } catch (\Exception $e) {
                Yii::error($e->getMessage());
            }

            $cache->set($cacheKey, $hotspotResults, 900); // Cache 15 phút
        }

        return $hotspotResults;
    }
    
    private function getKpiData()
    {
        $total = VuViec::find()->count();
        $overdue = VuViec::find()->joinWith('trangThaiHienTai ts')->where(['<', 'han_xu_ly', new Expression('NOW()')])->andWhere(['<>', 'ts.ten_trang_thai', 'Đã giải quyết'])->count();
        $newToday = VuViec::find()->where(['>=', 'ngay_tiep_nhan', new Expression('CURRENT_DATE')])->count();
        return compact('total', 'overdue', 'newToday');
    }

    private function getChartData()
    {
        $topWards = VuViec::find()->select(['px.ten_dvhc', 'COUNT(vu_viec.id) as total'])->joinWith('phuongXa px')->groupBy('px.ten_dvhc')->orderBy(['total' => SORT_DESC])->limit(5)->asArray()->all();
        $byDomain = VuViec::find()->select(['lv.ten_linh_vuc', 'COUNT(vu_viec.id) as total'])->joinWith('linhVuc lv')->groupBy('lv.ten_linh_vuc')->orderBy(['total' => SORT_DESC])->asArray()->all();
        return compact('topWards', 'byDomain');
    }
}
