<?php
/**
 * @var yii\web\View $this
 * @var array $kpis
 * @var array $statusChartData
 * @var array $linhVucChartData
 * @var array $trendChartData
 * @var app\modules\quanly\models\VuViec[] $topCanhBaoDo
 * @var app\modules\quanly\models\VuViec[] $topQuaHan
 */

use yii\helpers\Html;
use yii\helpers\Json;

$this->title = 'Dashboard Tổng Quan';
$this->params['breadcrumbs'][] = $this->title;

// Dữ liệu được chuyển từ Controller sang Javascript
$statusChartDataJson = Json::encode($statusChartData);
$linhVucChartDataJson = Json::encode($linhVucChartData);
$trendChartDataJson = Json::encode($trendChartData);
?>

<!-- Include CSS & JS for libraries -->
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<style>
    body {
        background-color: #f8fafc; /* slate-50 */
    }
    .card {
        background-color: white;
        border-radius: 0.75rem; /* rounded-xl */
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease-in-out;
    }
    .card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        transform: translateY(-4px);
    }
    .kpi-card .icon-wrapper {
        padding: 0.75rem; /* p-3 */
        border-radius: 9999px; /* rounded-full */
    }
    .list-item {
        border-bottom: 1px solid #e5e7eb; /* border-gray-200 */
        padding: 1rem 0.5rem;
        transition: background-color 0.2s ease;
    }
    .list-item:last-child {
        border-bottom: none;
    }
    .list-item:hover {
        background-color: #f9fafb; /* gray-50 */
    }
</style>

<div class="quanly-dashboard p-4 sm:p-6 lg:p-8">
    
    <!-- Header -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Dashboard Tổng Quan</h1>
            <p class="text-slate-500 mt-1">Báo cáo tổng hợp tình hình quản lý khu vực.</p>
        </div>
        <div class="text-sm text-slate-500 flex items-center bg-white px-4 py-2 rounded-lg shadow-sm">
            <i data-lucide="clock" class="w-4 h-4 mr-2 text-slate-400"></i>
            <span>Cập nhật lúc: <?= date('d/m/Y H:i') ?></span>
        </div>
    </div>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- === Left Column (Main Content) === -->
        <div class="lg:col-span-2 space-y-6">
            <!-- KPIs Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <!-- KPI Items -->
                <?php
                $kpiItems = [
                    ['label' => 'Tổng Vụ Việc', 'value' => $kpis['totalVuViec'], 'icon' => 'layers', 'color' => 'blue'],
                    ['label' => 'Cảnh Báo Đỏ', 'value' => $kpis['highRisk'], 'icon' => 'shield-alert', 'color' => 'red'],
                    ['label' => 'Tổng Hộ Gia Đình', 'value' => $kpis['totalHoGiaDinh'], 'icon' => 'home', 'color' => 'green'],
                    ['label' => 'Tổng Nóc Gia', 'value' => $kpis['totalNocGia'], 'icon' => 'building-2', 'color' => 'purple'],
                    ['label' => 'Tổng Nhân Khẩu', 'value' => $kpis['totalNguoiDan'], 'icon' => 'users', 'color' => 'sky'],
                    ['label' => 'Điểm Nhạy Cảm', 'value' => $kpis['totalDiemNhayCam'], 'icon' => 'siren', 'color' => 'orange'],
                    ['label' => 'Điểm Trọng Điểm', 'value' => $kpis['totalDiemTrongDiem'], 'icon' => 'target', 'color' => 'teal'],
                    ['label' => 'Phường/Xã', 'value' => $kpis['totalPhuongXa'], 'icon' => 'map-pin', 'color' => 'indigo'],
                ];
                $colors = [
                    'blue' => 'text-blue-600 bg-blue-100', 'red' => 'text-red-600 bg-red-100',
                    'green' => 'text-green-600 bg-green-100', 'purple' => 'text-purple-600 bg-purple-100',
                    'sky' => 'text-sky-600 bg-sky-100', 'orange' => 'text-orange-600 bg-orange-100',
                    'teal' => 'text-teal-600 bg-teal-100', 'indigo' => 'text-indigo-600 bg-indigo-100',
                ];
                ?>
                <?php foreach ($kpiItems as $item): ?>
                <div class="card kpi-card p-4 flex items-center">
                    <div class="icon-wrapper <?= $colors[$item['color']] ?> mr-4">
                        <i data-lucide="<?= $item['icon'] ?>"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-700"><?= $item['value'] ?></div>
                        <div class="text-sm text-slate-500"><?= $item['label'] ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Trend Chart -->
            <div class="card p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-slate-700 mb-4">Xu Hướng Vụ Việc (30 Ngày Qua)</h2>
                <div class="h-72">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <!-- Linh Vuc Chart -->
            <div class="card p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-slate-700 mb-4">Thống Kê Vụ Việc Theo Lĩnh Vực</h2>
                 <div class="h-80">
                    <canvas id="linhVucChart"></canvas>
                </div>
            </div>
        </div>

        <!-- === Right Column (Side Content) === -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Status Chart -->
            <div class="card p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-slate-700 mb-4">Phân Bố Trạng Thái</h2>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <!-- High Risk List -->
            <div class="card p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-slate-700 mb-4 flex items-center">
                    <i data-lucide="alert-triangle" class="text-red-500 mr-2"></i> Cần Quan Tâm
                </h2>
                <div>
                    <?php if (empty($topCanhBaoDo)): ?>
                        <p class="text-center text-slate-500 py-4">Không có vụ việc cảnh báo đỏ.</p>
                    <?php else: ?>
                        <?php foreach ($topCanhBaoDo as $vuviec): ?>
                            <div class="list-item">
                                <p class="font-semibold text-slate-700 truncate"><?= Html::encode($vuviec->tom_tat_noi_dung) ?></p>
                                <p class="text-sm text-slate-500"><?= Html::encode($vuviec->linhVuc->ten_linh_vuc ?? 'N/A') ?> tại <?= Html::encode($vuviec->phuongXa->tenXa ?? 'N/A') ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Overdue List -->
            <div class="card p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-slate-700 mb-4 flex items-center">
                    <i data-lucide="timer-off" class="text-amber-500 mr-2"></i> Vụ Việc Quá Hạn
                </h2>
                <div>
                     <?php if (empty($topQuaHan)): ?>
                        <p class="text-center text-slate-500 py-4">Không có vụ việc nào quá hạn.</p>
                    <?php else: ?>
                        <?php foreach ($topQuaHan as $vuviec): ?>
                            <div class="list-item">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-slate-700"><?= Html::encode($vuviec->tom_tat_noi_dung) ?></p>
                                        <p class="text-sm text-slate-500">Hạn: <span class="font-medium text-red-600"><?= Yii::$app->formatter->asDate($vuviec->han_xu_ly, 'dd/MM/yyyy') ?></span></p>
                                    </div>
                                    <span class="text-xs font-medium bg-amber-100 text-amber-800 px-2 py-1 rounded-full"><?= Html::encode($vuviec->trangThaiHienTai->ten_trang_thai ?? 'N/A') ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    lucide.createIcons();

    const chartConfig = {
        plugins: {
            legend: {
                labels: {
                    color: '#475569', // slate-600
                    font: { family: 'inherit' }
                }
            }
        },
        scales: {
            y: { 
                beginAtZero: true, 
                grid: { color: '#e2e8f0' }, // slate-200
                ticks: { color: '#64748b' } // slate-500
            },
            x: { 
                grid: { display: false },
                ticks: { color: '#64748b' } // slate-500
            }
        },
        maintainAspectRatio: false
    };

    // 1. Trend Chart (Line)
    const trendCtx = document.getElementById('trendChart').getContext('2d');
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
                borderColor: '#2563eb', // blue-600
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#2563eb',
            }]
        },
        options: { ...chartConfig, plugins: { legend: { display: false } } }
    });
    
    // 2. Linh Vuc Chart (Bar)
    const linhVucCtx = document.getElementById('linhVucChart').getContext('2d');
    const linhVucData = <?= $linhVucChartDataJson ?>;
    new Chart(linhVucCtx, {
        type: 'bar',
        data: {
            labels: linhVucData.labels,
            datasets: [{
                label: 'Số vụ việc',
                data: linhVucData.data,
                backgroundColor: ['#3b82f6', '#ef4444', '#22c55e', '#a855f7', '#06b6d4', '#f97316', '#14b8a6', '#6366f1'],
                borderRadius: 4,
                barThickness: 20,
            }]
        },
        options: { ...chartConfig, indexAxis: 'y', plugins: { legend: { display: false } } }
    });

    // 3. Status Chart (Doughnut)
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusData = <?= $statusChartDataJson ?>;
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: statusData.labels,
            datasets: [{
                data: statusData.data,
                backgroundColor: ['#3b82f6', '#ef4444', '#22c55e', '#a855f7', '#eab308'],
                borderColor: '#ffffff',
                borderWidth: 4,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } },
            cutout: '70%'
        }
    });
});
</script>

