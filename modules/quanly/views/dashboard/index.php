<?php
/**
 * BẢNG ĐIỀU HÀNH NGHIỆP VỤ ANTT (CHO CÔNG AN PHƯỜNG) - v3
 * Thiết kế theo 6 lớp chuyên đề, bỏ bản đồ, bỏ lọc phường.
 *
 * @var yii\web\View $this
 * @var array $kpis
 * @var array $topCanhBaoDo
 * @var array $topQuaHan
 * @var array $layerData (Dữ liệu 6 lớp chuyên đề)
 * @var array $trendChartData
 * @var array $statusChartData
 */

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = 'Bảng điều hành nghiệp vụ ANTT'; // Đã bỏ tên phường
$this->params['breadcrumbs'][] = $this->title;

// Mã hóa dữ liệu để truyền cho JavaScript
$trendChartDataJson = Json::encode($trendChartData);
$statusChartDataJson = Json::encode($statusChartData);

// URL Bản đồ
$mapUrl = Url::to(['/quanly/map/vuviec']);

// Helper function để hiển thị badge cho 6 lớp
function get_badge_class($layerName, $badgeText) {
    $base = "badge text-xs font-semibold px-2 py-0.5 rounded-full ";
    switch ($layerName) {
        case 'anNinh':
            return $base . ($badgeText == 'Cao' ? 'bg-red-100 text-red-800' : 'bg-purple-100 text-purple-800');
        case 'tratTuXaHoi':
            return $base . 'bg-orange-100 text-orange-800';
        case 'quanLyDanCu':
            return $base . 'bg-indigo-100 text-indigo-800';
        case 'tuanTraGiamSat':
            return $base . ($badgeText == 'Offline' ? 'bg-gray-200 text-gray-800' : 'bg-blue-100 text-blue-800');
        case 'vuViec':
            return $base . 'bg-blue-100 text-blue-800';
        case 'pccc':
            return $base . (str_contains((string)$badgeText, 'Cao') ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800');
        default:
            return $base . 'bg-gray-100 text-gray-800';
    }
}
?>

<!-- Include CSS & JS for libraries -->
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<style>
    body {
        background-color: #f1f5f9; /* slate-100 */
    }
    .card {
        background-color: white;
        border-radius: 0.75rem; /* rounded-xl */
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px 0 rgba(0, 0, 0, 0.02);
        border: 1px solid #e2e8f0; /* slate-200 */
    }
    .card-title {
        font-size: 1.125rem; /* text-lg */
        font-weight: 600; /* font-semibold */
        color: #1e293b; /* slate-800 */
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0; /* slate-200 */
        display: flex;
        align-items: center;
    }
    .card-title i {
        width: 1.25rem;
        height: 1.25rem;
        margin-right: 0.75rem;
    }
    .card-content {
        padding: 1rem 1.5rem;
    }
    .kpi-card {
        display: flex;
        align-items: center;
        padding: 1.25rem; /* p-5 */
    }
    .kpi-icon {
        padding: 0.875rem; /* p-3.5 */
        border-radius: 9999px; /* rounded-full */
        margin-right: 1.25rem; /* mr-5 */
    }
    .kpi-icon i {
        width: 1.75rem; /* w-7 */
        height: 1.75rem; /* h-7 */
    }
    .kpi-value {
        font-size: 2rem; /* text-3xl */
        font-weight: 700; /* font-bold */
        color: #1e293b; /* slate-800 */
    }
    .kpi-label {
        font-size: 0.875rem; /* text-sm */
        color: #64748b; /* slate-500 */
    }
    .list-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid #e2e8f0; /* slate-200 */
    }
    .list-item:last-child { border-bottom: none; padding-bottom: 0; }
    .list-item:first-child { padding-top: 0; }
    
    .badge {
        display: inline-block;
        font-size: 0.75rem; /* text-xs */
        font-weight: 500; /* font-medium */
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
    }
    .layer-card-title {
        font-size: 1rem; /* text-base */
        font-weight: 600;
        color: #334155; /* slate-700 */
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }
    .layer-card-title i {
        width: 1.125rem; /* w-4.5 */
        height: 1.125rem; /* h-4.5 */
        margin-right: 0.5rem;
    }
    .layer-kpi-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem 1rem;
        margin-bottom: 1rem;
    }
    .layer-kpi-item {
        display: flex;
        align-items: baseline;
    }
    .layer-kpi-value {
        font-size: 1.25rem; /* text-xl */
        font-weight: 600;
        color: #1e293b; /* slate-800 */
        margin-right: 0.25rem;
    }
    .layer-kpi-label {
        font-size: 0.875rem; /* text-sm */
        color: #64748b; /* slate-500 */
    }
    .layer-sublist-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-top: 1px solid #f1f5f9; /* slate-100 */
    }
    .layer-sublist-item:first-child { border-top: none; padding-top: 0; }
</style>

<div class="quanly-dashboard p-4 sm:p-6 lg:p-8 space-y-6">
    
    <!-- === Header === -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-2">
        <div>
            <h1 class="text-3xl font-bold text-slate-800"><?= Html::encode($this->title) ?></h1>
            <p class="text-slate-500 mt-1">Bảng điều hành nghiệp vụ ANTT Phường Thái Bình, Tỉnh Hưng Yên.</p>
        </div>
        <div class="flex gap-3 items-center">
            <!-- Đã gỡ bỏ ô "Địa bàn" -->
            <!-- Nút bấm chuyển sang bản đồ -->
            <a href="<?= Html::encode($mapUrl) ?>" target="_blank" class="flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg shadow-sm transition-colors">
                <i data-lucide="map" class="w-4 h-4 mr-2"></i>
                Chuyển Tới Bản Đồ
            </a>
        </div>
    </div>

    <!-- === Hàng 1: KPIs Tác Nghiệp Chính === -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card kpi-card">
            <div class="kpi-icon bg-blue-100 text-blue-600"><i data-lucide="alert-circle"></i></div>
            <div>
                <div class="kpi-value"><?= $kpis['vuViecHomNay'] ?></div>
                <div class="kpi-label">Vụ việc mới trong ngày</div>
            </div>
        </div>
        <div class="card kpi-card">
            <div class="kpi-icon bg-red-100 text-red-600"><i data-lucide="shield-alert"></i></div>
            <div>
                <div class="kpi-value"><?= $kpis['canhBaoDoHoatDong'] ?></div>
                <div class="kpi-label">Cảnh báo Đỏ đang hoạt động</div>
            </div>
        </div>
        <div class="card kpi-card">
            <div class="kpi-icon bg-yellow-100 text-yellow-600"><i data-lucide="calendar-clock"></i></div>
            <div>
                <div class="kpi-value"><?= $kpis['sapDenHan'] ?></div>
                <div class="kpi-label">Vụ việc sắp đến hạn (3 ngày)</div>
            </div>
        </div>
        <div class="card kpi-card">
            <div class="kpi-icon bg-indigo-100 text-indigo-600"><i data-lucide="users"></i></div>
            <div>
                <div class="kpi-value"><?= $kpis['doiTuongQuanTam'] ?></div>
                <div class="kpi-label">Đối tượng cần quan tâm</div>
            </div>
        </div>
    </div>

    <!-- === Hàng 2: Trung tâm Tác nghiệp (Cảnh báo & Quá hạn) === -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Vụ việc Cảnh báo Đỏ -->
        <div class="card">
            <h2 class="card-title text-red-600">
                <i data-lucide="alert-triangle"></i>
                Trung tâm Cảnh báo Đỏ
            </h2>
            <div class="card-content pt-0">
                <?php if (empty($topCanhBaoDo)): ?>
                    <p class="text-center text-slate-500 py-4">Không có cảnh báo đỏ nào đang hoạt động.</p>
                <?php else: foreach ($topCanhBaoDo as $vuviec): ?>
                    <div class="list-item">
                        <p class="font-semibold text-slate-700 truncate"><?= Html::encode($vuviec->tom_tat_noi_dung) ?></p>
                        <div class="flex justify-between text-sm mt-1">
                            <span class="text-slate-500">Lĩnh vực: <span class="font-medium text-slate-600"><?= Html::encode($vuviec->linhVuc->ten_linh_vuc ?? 'N/A') ?></span></span>
                            <span class="text-slate-500">Người báo: <span class="font-medium text-slate-600"><?= Html::encode($vuviec->nguoiDan->ho_ten ?? 'N/A') ?></span></span>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
        
        <!-- Vụ việc Quá hạn Xử lý -->
        <div class="card">
            <h2 class="card-title text-yellow-600">
                <i data-lucide="timer-off"></i>
                Vụ việc Quá hạn Xử lý
            </h2>
             <div class="card-content pt-0">
                <?php if (empty($topQuaHan)): ?>
                    <p class="text-center text-slate-500 py-4">Không có vụ việc nào quá hạn.</p>
                <?php else: foreach ($topQuaHan as $vuviec): ?>
                    <div class="list-item">
                        <p class="font-semibold text-slate-700 truncate"><?= Html::encode($vuviec->tom_tat_noi_dung) ?></p>
                        <div class="flex justify-between text-sm mt-1">
                            <span class="text-slate-500">Hạn xử lý: <span class="font-medium text-red-600"><?= Yii::$app->formatter->asDate($vuviec->han_xu_ly, 'dd/MM/yyyy') ?></span></span>
                            <span class="text-slate-500">Cán bộ: <span class="font-medium text-slate-600"><?= Html::encode($vuviec->canBoTiepNhan->ho_ten ?? 'N/A') ?></span></span>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>

    <!-- === Hàng 3: 6 Lớp Chuyên Đề Nghiệp Vụ === -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Lớp Vụ Việc -->
        <div class="card">
            <div class="card-content">
                <h3 class="layer-card-title text-blue-600"><i data-lucide="alert-circle"></i>Lớp Vụ Việc</h3>
                <div class="layer-kpi-list">
                    <?php foreach ($layerData['vuViec']['counts'] as $label => $value): ?>
                    <div class="layer-kpi-item">
                        <span class="layer-kpi-value"><?= $value ?></span>
                        <span class="layer-kpi-label"><?= $label ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="space-y-2">
                    <?php foreach ($layerData['vuViec']['list'] as $item): ?>
                    <div class="layer-sublist-item">
                        <span class="text-sm text-slate-600 truncate pr-2"><?= Html::encode($item->tom_tat_noi_dung) ?></span>
                        <span class="<?= get_badge_class('vuViec', $item->linhVuc->ten_linh_vuc ?? '') ?> truncate"><?= Html::encode($item->linhVuc->ten_linh_vuc ?? 'N/A') ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Lớp An Ninh -->
        <div class="card">
            <div class="card-content">
                <h3 class="layer-card-title text-purple-600"><i data-lucide="shield"></i>Lớp An Ninh</h3>
                <div class="layer-kpi-list">
                    <?php foreach ($layerData['anNinh']['counts'] as $label => $value): ?>
                    <div class="layer-kpi-item">
                        <span class="layer-kpi-value"><?= $value ?></span>
                        <span class="layer-kpi-label"><?= $label ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="space-y-2">
                    <?php foreach ($layerData['anNinh']['list'] as $item): ?>
                    <div class="layer-sublist-item">
                        <span class="text-sm text-slate-600 truncate pr-2"><?= Html::encode($item->ten) ?></span>
                        <span class="<?= get_badge_class('anNinh', $item->muc_do_phuctap) ?> truncate"><?= Html::encode($item->muc_do_phuctap) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Lớp Trật Tự Xã Hội -->
        <div class="card">
            <div class="card-content">
                <h3 class="layer-card-title text-orange-600"><i data-lucide="store"></i>Lớp Trật Tự Xã Hội</h3>
                <div class="layer-kpi-list">
                    <?php foreach ($layerData['tratTuXaHoi']['counts'] as $label => $value): ?>
                    <div class="layer-kpi-item">
                        <span class="layer-kpi-value"><?= $value ?></span>
                        <span class="layer-kpi-label"><?= $label ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="space-y-2">
                    <?php foreach ($layerData['tratTuXaHoi']['list'] as $item): ?>
                    <div class="layer-sublist-item">
                        <span class="text-sm text-slate-600 truncate pr-2"><?= Html::encode($item->ten_co_so) ?></span>
                        <span class="<?= get_badge_class('tratTuXaHoi', $item->loai_hinh_kinh_doanh) ?> truncate"><?= Html::encode($item->loai_hinh_kinh_doanh) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- Lớp Quản Lý Dân Cư -->
        <div class="card">
            <div class="card-content">
                <h3 class="layer-card-title text-indigo-600"><i data-lucide="users"></i>Lớp Quản Lý Dân Cư</h3>
                <div class="layer-kpi-list">
                    <?php foreach ($layerData['quanLyDanCu']['counts'] as $label => $value): ?>
                    <div class="layer-kpi-item">
                        <span class="layer-kpi-value"><?= $value ?></span>
                        <span class="layer-kpi-label"><?= $label ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="space-y-2">
                    <?php foreach ($layerData['quanLyDanCu']['list'] as $item): ?>
                    <div class="layer-sublist-item">
                        <span class="text-sm text-slate-600 truncate pr-2"><?= Html::encode($item->ho_ten) ?></span>
                        <span class="<?= get_badge_class('quanLyDanCu', $item->nhom_doi_tuong) ?> truncate"><?= Html::encode($item->nhom_doi_tuong) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- Lớp Tuần Tra - Giám Sát -->
        <div class="card">
            <div class="card-content">
                <h3 class="layer-card-title text-cyan-600"><i data-lucide="video"></i>Lớp Tuần Tra - Giám Sát</h3>
                <div class="layer-kpi-list">
                    <?php foreach ($layerData['tuanTraGiamSat']['counts'] as $label => $value): ?>
                    <div class="layer-kpi-item">
                        <span class="layer-kpi-value"><?= $value ?></span>
                        <span class="layer-kpi-label"><?= $label ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="space-y-2">
                    <?php foreach ($layerData['tuanTraGiamSat']['list'] as $item): ?>
                    <div class="layer-sublist-item">
                        <span class="text-sm text-slate-600 truncate pr-2"><?= Html::encode($item->ten_diem) ?></span>
                        <span class="<?= get_badge_class('tuanTraGiamSat', $item->trang_thai) ?> truncate"><?= Html::encode($item->trang_thai) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- Lớp PCCC -->
        <div class="card">
            <div class="card-content">
                <h3 class="layer-card-title text-red-600"><i data-lucide="flame"></i>Lớp PCCC</h3>
                <div class="layer-kpi-list">
                    <?php foreach ($layerData['pccc']['counts'] as $label => $value): ?>
                    <div class="layer-kpi-item">
                        <span class="layer-kpi-value"><?= $value ?></span>
                        <span class="layer-kpi-label"><?= $label ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="space-y-2">
                    <?php foreach ($layerData['pccc']['list'] as $item): ?>
                    <div class="layer-sublist-item">
                        <span class="text-sm text-slate-600 truncate pr-2"><?= Html::encode($item->ten_co_so) ?></span>
                        <span class="<?= get_badge_class('pccc', $item->muc_do_nguy_co) ?> truncate"><?= Html::encode($item->muc_do_nguy_co) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>

    <!-- === Hàng 4: Biểu đồ Phân Tích === -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Xu Hướng Vụ Việc -->
        <div class="card">
            <h2 class="card-title"><i data-lucide="line-chart" class="text-blue-500"></i>Xu Hướng Vụ Việc (30 Ngày Qua)</h2>
            <div class="card-content h-72"><canvas id="trendChart"></canvas></div>
        </div>

        <!-- Tình Trạng Xử Lý -->
        <div class="card">
            <h2 class="card-title"><i data-lucide="pie-chart" class="text-green-500"></i>Tình Trạng Xử Lý Vụ Việc</h2>
            <div class="card-content h-72 flex items-center justify-center"><canvas id="statusChart"></canvas></div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    lucide.createIcons();

    // === KHỞI TẠO BIỂU ĐỒ ===
    const chartConfig = {
        plugins: { 
            legend: { 
                labels: { color: '#475569', font: { family: 'inherit' } },
                position: 'bottom'
            } 
        },
        scales: {
            y: { beginAtZero: true, grid: { color: '#e2e8f0' }, ticks: { color: '#64748b' } },
            x: { grid: { display: false }, ticks: { color: '#64748b' } }
        },
        maintainAspectRatio: false,
        responsive: true
    };
    const chartColors = ['#3b82f6', '#ef4444', '#22c55e', '#a855f7', '#06b6d4', '#f97316', '#14b8a6', '#6366f1'];

    // 1. Trend Chart (Line)
    const trendCtx = document.getElementById('trendChart')?.getContext('d');
    if (trendCtx) {
        const trendData = <?= $trendChartDataJson ?>;
        const gradient = trendCtx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.5)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: trendData.labels,
                datasets: [{
                    label: 'Vụ việc mới',
                    data: trendData.data,
                    borderColor: '#2563eb',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#2563eb',
                }]
            },
            options: { ...chartConfig, plugins: { legend: { display: false } } }
        });
    }
    
    // 2. Status Chart (Doughnut)
    const statusCtx = document.getElementById('statusChart')?.getContext('d');
    if (statusCtx) {
        const statusData = <?= $statusChartDataJson ?>;
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusData.labels,
                datasets: [{
                    data: statusData.data,
                    backgroundColor: chartColors,
                    borderColor: '#ffffff',
                    borderWidth: 4,
                    hoverOffset: 8
                }]
            },
            options: { 
                responsive: true, 
                plugins: { legend: { position: 'bottom', labels: { color: '#475569', font: { family: 'inherit' } } } }, 
                cutout: '70%' 
            }
        });
    }
});
</script>

